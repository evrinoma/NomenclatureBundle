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

class DecoratorPass extends AbstractRecursivePass
{
    private array $services = ['nomenclature'];

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->services as $alias) {
            $this->wireDecorates($container, $alias);
        }
    }

    private function wireDecorates(ContainerBuilder $container, string $name)
    {
        $decoratorQuery = $container->hasParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.decorates.query');
        if ($decoratorQuery) {
            $decoratorQuery = $container->getParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.decorates.query');
            $queryMediator = $container->getDefinition($decoratorQuery);
            $repository = $container->getDefinition('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.repository');
            $repository->setArgument(2, $queryMediator);
        }
        $decoratorCommand = $container->hasParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.decorates.command');
        if ($decoratorCommand) {
            $decoratorCommand = $container->getParameter('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.decorates.command');
            $commandMediator = $container->getDefinition($decoratorCommand);
            $commandManager = $container->getDefinition('evrinoma.'.EvrinomaNomenclatureBundle::BUNDLE.'.'.$name.'.command.manager');
            $commandManager->setArgument(3, $commandMediator);
        }
    }
}
