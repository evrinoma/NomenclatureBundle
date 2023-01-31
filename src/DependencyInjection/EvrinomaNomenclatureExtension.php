<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\NomenclatureBundle\DependencyInjection;

use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Property\ItemPass as PropertyItemPass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Property\NomenclaturePass as PropertyNomenclaturePass;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDto;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto;
use Evrinoma\NomenclatureBundle\Entity\Item\BaseItem;
use Evrinoma\NomenclatureBundle\Entity\Nomenclature\BaseNomenclature;
use Evrinoma\NomenclatureBundle\EvrinomaNomenclatureBundle;
use Evrinoma\NomenclatureBundle\Factory\Nomenclature\Factory as NomenclatureFactory;
use Evrinoma\NomenclatureBundle\Mediator\Item\QueryMediatorInterface as ItemQueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Mediator\Nomenclature\QueryMediatorInterface as NomenclatureQueryMediatorInterface;
use Evrinoma\UtilsBundle\Adaptor\AdaptorRegistry;
use Evrinoma\UtilsBundle\DependencyInjection\HelperTrait;
use Evrinoma\UtilsBundle\Handler\BaseHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class EvrinomaNomenclatureExtension extends Extension
{
    use HelperTrait;

    public const ENTITY = 'Evrinoma\NomenclatureBundle\Entity';
    public const MODEL = 'Evrinoma\NomenclatureBundle\Model';
    public const ENTITY_FACTORY_NOMENCLATURE = NomenclatureFactory::class;
    public const ENTITY_BASE_NOMENCLATURE = BaseNomenclature::class;
    public const DTO_BASE_NOMENCLATURE = NomenclatureApiDto::class;
    public const HANDLER = BaseHandler::class;

    /**
     * @var array
     */
    private static array $doctrineDrivers = [
        'orm' => [
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ],
    ];

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ('prod' !== $container->getParameter('kernel.environment')) {
            $loader->load('fixtures.yml');
        }

        if ('test' === $container->getParameter('kernel.environment')) {
            $loader->load('tests.yml');
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (self::ENTITY_FACTORY_NOMENCLATURE !== $config['factory']) {
            $this->wireFactory($container, $config['factory'], $config['entity']);
        } else {
            $definitionFactory = $container->getDefinition('evrinoma.'.$this->getAlias().'.nomenclature.factory');
            $definitionFactory->setArgument(0, $config['entity']);
        }

        $registry = null;

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'orm' === $config['db_driver']) {
            $loader->load('doctrine.yml');
            $container->setAlias('evrinoma.'.$this->getAlias().'.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            $registry = new Reference('evrinoma.'.$this->getAlias().'.doctrine_registry');
            $container->setParameter('evrinoma.'.$this->getAlias().'.backend_type_'.$config['db_driver'], true);
            $objectManager = $container->getDefinition('evrinoma.'.$this->getAlias().'.object_manager');
            $objectManager->setFactory([$registry, 'getManager']);
        }

        if (isset(self::$doctrineDrivers[$config['db_driver']]) && 'api' === $config['db_driver']) {
            // @ToDo
        }

        if (null !== $registry) {
            $this->wireAdaptorRegistry($container, $registry);
        }

        $this->wireMediator($container, NomenclatureQueryMediatorInterface::class, $config['db_driver'], 'nomenclature');
        $this->wireMediator($container, ItemQueryMediatorInterface::class, $config['db_driver'], 'item');

        $this->remapParametersNamespaces(
            $container,
            $config,
            [
                '' => [
                    'db_driver' => 'evrinoma.'.$this->getAlias().'.storage',
                    'entity' => 'evrinoma.'.$this->getAlias().'.entity',
                ],
            ]
        );

        if ($registry && isset(self::$doctrineDrivers[$config['db_driver']])) {
            $this->wireRepository($container, $registry, NomenclatureQueryMediatorInterface::class, 'nomenclature', $config['entity'], $config['db_driver']);
            $this->wireRepository($container, $registry, ItemQueryMediatorInterface::class, 'item', BaseItem::class, $config['db_driver']);
        }

        $this->wireController($container, 'nomenclature', $config['dto']);
        $this->wireController($container, 'item', ItemApiDto::class);

        $this->wireValidator($container, 'nomenclature', $config['entity']);
        $this->wireValidator($container, 'item', BaseItem::class);

        if ($config['constraints']) {
            $loader->load('validation.yml');
        }

        $this->wireConstraintTag($container);

        $this->wireForm($container, ItemApiDto::class, 'item', 'item');

        if ($config['decorates']) {
            $remap = [];
            foreach ($config['decorates'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'command':
                            $remap['command'] = 'evrinoma.'.$this->getAlias().'.nomenclature.decorates.command';
                            break;
                        case 'query':
                            $remap['query'] = 'evrinoma.'.$this->getAlias().'.nomenclature.decorates.query';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['decorates'],
                ['' => $remap]
            );
        }

        if ($config['services']) {
            $remap = [];
            foreach ($config['services'] as $key => $service) {
                if (null !== $service) {
                    switch ($key) {
                        case 'pre_validator':
                            $remap['pre_validator'] = 'evrinoma.'.$this->getAlias().'.nomenclature.services.pre.validator';
                            break;
                        case 'handler':
                            $remap['handler'] = 'evrinoma.'.$this->getAlias().'.nomenclature.services.handler';
                            break;
                        case 'file_system':
                            $remap['file_system'] = 'evrinoma.'.$this->getAlias().'.nomenclature.services.system.item_system';
                            break;
                    }
                }
            }

            $this->remapParametersNamespaces(
                $container,
                $config['services'],
                ['' => $remap]
            );
        }
    }

    private function wireConstraintTag(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $key => $definition) {
            switch (true) {
                case false !== str_contains($key, PropertyNomenclaturePass::NOMENCLATURE_CONSTRAINT):
                    $definition->addTag(PropertyNomenclaturePass::NOMENCLATURE_CONSTRAINT);
                    break;
                case false !== str_contains($key, PropertyItemPass::ITEM_CONSTRAINT):
                    $definition->addTag(PropertyItemPass::ITEM_CONSTRAINT);
                    break;
//                case false !== strpos($key, NomenclaturePass::NOMENCLATURE_CONSTRAINT):
//                    $definition->addTag(NomenclaturePass::NOMENCLATURE_CONSTRAINT);
//                    break;
                default:
            }
        }
    }

    private function wireForm(ContainerBuilder $container, string $class, string $name, string $form): void
    {
        $definitionBridgeCreate = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.form.rest.'.$form);
        $definitionBridgeCreate->setArgument(1, $class);
    }

    private function wireMediator(ContainerBuilder $container, string $class, string $driver, string $name): void
    {
        $definitionQueryMediator = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.query.'.$driver.'.mediator');
        $container->addDefinitions([$class => $definitionQueryMediator]);
    }

    private function wireAdaptorRegistry(ContainerBuilder $container, Reference $registry): void
    {
        $definitionAdaptor = new Definition(AdaptorRegistry::class);
        $definitionAdaptor->addArgument($registry);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.adaptor' => $definitionAdaptor]);
    }

    private function wireRepository(ContainerBuilder $container, Reference $registry, string $madiator, string $name, string $class, string $driver): void
    {
        $definitionRepository = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.'.$driver.'.repository');
        $definitionQueryMediator = $container->getDefinition($madiator);
        $definitionRepository->setArgument(0, $registry);
        $definitionRepository->setArgument(1, $class);
        $definitionRepository->setArgument(2, $definitionQueryMediator);
        $array = $definitionRepository->getArguments();
        ksort($array);
        $definitionRepository->setArguments($array);
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.repository' => $definitionRepository]);
    }

    private function wireFactory(ContainerBuilder $container, string $name, string $class, string $paramClass): void
    {
        $container->removeDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $definitionFactory = new Definition($class);
        $definitionFactory->addArgument($paramClass);
        $alias = new Alias('evrinoma.'.$this->getAlias().'.'.$name.'.factory');
        $container->addDefinitions(['evrinoma.'.$this->getAlias().'.'.$name.'.factory' => $definitionFactory]);
        $container->addAliases([$class => $alias]);
    }

    private function wireController(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.api.controller');
        $definitionApiController->setArgument(4, $class);
    }

    private function wireValidator(ContainerBuilder $container, string $name, string $class): void
    {
        $definitionApiController = $container->getDefinition('evrinoma.'.$this->getAlias().'.'.$name.'.validator');
        $definitionApiController->setArgument(0, new Reference('validator'));
        $definitionApiController->setArgument(1, $class);
    }

    public function getAlias()
    {
        return EvrinomaNomenclatureBundle::BUNDLE;
    }
}
