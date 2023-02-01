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

use Evrinoma\NomenclatureBundle\EvrinomaNomenclatureBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(EvrinomaNomenclatureBundle::BUNDLE);
        $rootNode = $treeBuilder->getRootNode();
        $supportedDrivers = ['orm'];

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('db_driver')
            ->validate()
            ->ifNotInArray($supportedDrivers)
            ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
            ->end()
            ->cannotBeOverwritten()
            ->defaultValue('orm')
            ->end()
            ->scalarNode('factory_nomenclature')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::ENTITY_FACTORY_NOMENCLATURE)->end()
            ->scalarNode('factory_item')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::ENTITY_FACTORY_ITEM)->end()
            ->scalarNode('entity_nomenclature')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::ENTITY_BASE_NOMENCLATURE)->end()
            ->scalarNode('entity_item')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::ENTITY_BASE_ITEM)->end()
            ->scalarNode('constraints')->defaultTrue()->info('This option is used to enable/disable basic nomenclature constraints')->end()
            ->scalarNode('dto_nomenclature')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::DTO_BASE_NOMENCLATURE)->info('This option is used to dto class override')->end()
            ->scalarNode('dto_item')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::DTO_BASE_ITEM)->info('This option is used to dto class override')->end()
            ->arrayNode('decorates')->addDefaultsIfNotSet()->children()
            ->scalarNode('command_nomenclature')->defaultNull()->info('This option is used to command nomenclature decoration')->end()
            ->scalarNode('query_nomenclature')->defaultNull()->info('This option is used to query nomenclature decoration')->end()
            ->scalarNode('command_item')->defaultNull()->info('This option is used to command nomenclature item decoration')->end()
            ->scalarNode('query_item')->defaultNull()->info('This option is used to query nomenclature item decoration')->end()
            ->end()->end()
            ->arrayNode('services')->addDefaultsIfNotSet()->children()
            ->scalarNode('pre_validator_nomenclature')->defaultNull()->info('This option is used to pre_validator overriding')->end()
            ->scalarNode('handler_nomenclature')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::HANDLER)->info('This option is used to handler override')->end()
            ->scalarNode('pre_validator_item')->defaultNull()->info('This option is used to pre_validator overriding')->end()
            ->scalarNode('handler_item')->cannotBeEmpty()->defaultValue(EvrinomaNomenclatureExtension::HANDLER)->info('This option is used to handler override')->end()
            ->scalarNode('file_system')->defaultNull()->info('This option is used to file system override')->end()
            ->end()->end()
            ->end();

        return $treeBuilder;
    }
}
