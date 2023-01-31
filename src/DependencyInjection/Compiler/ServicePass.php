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

namespace Evrinoma\NomenclatureBundle\DependencyInjection\Compiler;

use Evrinoma\NomenclatureBundle\EvrinomaNomenclatureBundle;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServicePass extends AbstractRecursivePass
{
    private array $services = ['nomenclature'];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $alias) {
            $this->wireServices($container, $alias);
        }
    }

    private function wireServices(ContainerBuilder $container, string $name)
    {
        $servicePreValidator = $container->hasParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.pre.validator');
        if ($servicePreValidator) {
            $servicePreValidator = $container->getParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.pre.validator');
            $preValidator = $container->getDefinition($servicePreValidator);
            $facade = $container->getDefinition('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.nomenclature.facade');
            $facade->setArgument(3, $preValidator);
        }
        $serviceHandler = $container->hasParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.handler');
        if ($serviceHandler) {
            $serviceHandler = $container->getParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.handler');
            $handler = $container->getDefinition($serviceHandler);
            $facade = $container->getDefinition('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.facade');
            $facade->setArgument(4, $handler);
        }
        $serviceFileSystem = $container->hasParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.system.item_system');
        if ($serviceFileSystem) {
            $serviceFileSystem = $container->getParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.services.system.item_system');
            $fileSystem = $container->getDefinition($serviceFileSystem);
            $commandMediator = $container->getDefinition('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.command.mediator');
            $commandMediator->setArgument(0, $fileSystem);
        }
    }
}
