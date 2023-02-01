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

use Evrinoma\NomenclatureBundle\DependencyInjection\EvrinomaNomenclatureExtension;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ('orm' === $container->getParameter('evrinoma.nomenclature.storage')) {
            $this->setContainer($container);

            $driver = $container->findDefinition('doctrine.orm.default_metadata_driver');
            $referenceAnnotationReader = new Reference('annotations.reader');

            $this->cleanMetadata($driver, [EvrinomaNomenclatureExtension::ENTITY]);

            $entityItem = $container->getParameter('evrinoma.nomenclature.entity_item');
            if (str_contains($entityItem, EvrinomaNomenclatureExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Item', '%s/Entity/Item');
            }

            $this->addResolveTargetEntity([$entityItem => [ItemInterface::class => []]], false);

            $mapping = $this->getManyToManyRelation();
            $this->addResolveTargetEntity([$entityItem => [ItemInterface::class => ['joinTable' => $mapping]]], false);

            $entityNomenclature = $container->getParameter('evrinoma.nomenclature.entity_nomenclature');
            if (str_contains($entityNomenclature, EvrinomaNomenclatureExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Nomenclature', '%s/Entity/Nomenclature');
            }

            $this->addResolveTargetEntity([$entityNomenclature => [NomenclatureInterface::class => []]], false);

            $mapping = $this->getManyToManyRelation();
            $this->addResolveTargetEntity([$entityNomenclature => [NomenclatureInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);
        }
    }

    private function getManyToManyRelation(): array
    {
        return ['name' => 'e_nomenclature_items_nomenclatures'];
    }
}
