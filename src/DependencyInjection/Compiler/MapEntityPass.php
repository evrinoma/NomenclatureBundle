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

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping;
use Evrinoma\NomenclatureBundle\DependencyInjection\EvrinomaNomenclatureExtension;
use Evrinoma\NomenclatureBundle\Entity\Item\BaseItem;
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

            $entityItem = BaseItem::class;

            $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Item', '%s/Entity/Item');

            $this->addResolveTargetEntity([$entityItem => [ItemInterface::class => []]], false);

            $entityNomenclature = $container->getParameter('evrinoma.nomenclature.entity');
            if (str_contains($entityNomenclature, EvrinomaNomenclatureExtension::ENTITY)) {
                $this->loadMetadata($driver, $referenceAnnotationReader, '%s/Model/Nomenclature', '%s/Entity/Nomenclature');
            }
            $this->addResolveTargetEntity([$entityNomenclature => [NomenclatureInterface::class => []]], false);

            $mapping = $this->getMapping($entityItem);
            $this->addResolveTargetEntity([$entityItem => [ItemInterface::class => ['inherited' => true, 'joinTable' => $mapping]]], false);
        }
    }

    private function getMapping(string $className): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $joinTableAttribute = $annotationReader->getClassAnnotation($reflectionClass, Mapping\Table::class);

        return ($joinTableAttribute) ? ['name' => $joinTableAttribute->name] : [];
    }
}
