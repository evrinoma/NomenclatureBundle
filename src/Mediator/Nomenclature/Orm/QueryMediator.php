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

namespace Evrinoma\NomenclatureBundle\Mediator\Nomenclature\Orm;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Mediator\Nomenclature\QueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Repository\AliasInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;
use Evrinoma\UtilsBundle\Mediator\Orm\QueryMediatorTrait;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
    use QueryMediatorTrait;

    protected static string $alias = AliasInterface::NOMENCLATURE;

    /**
     * @param DtoInterface          $dto
     * @param QueryBuilderInterface $builder
     *
     * @return mixed
     */
    public function createQuery(DtoInterface $dto, QueryBuilderInterface $builder): void
    {
        $alias = $this->alias();
        /** @var $dto NomenclatureApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias.'.id = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasTitle()) {
            $builder
                ->andWhere($alias.'.title like :title')
                ->setParameter('title', '%'.$dto->getTitle().'%');
        }

        if ($dto->hasDescription()) {
            $builder
                ->andWhere($alias.'.description like :description')
                ->setParameter('description', '%'.$dto->getDescription().'%');
        }

        if ($dto->hasPosition()) {
            $builder
                ->andWhere($alias.'.position = :position')
                ->setParameter('position', $dto->getPosition());
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias.'.active = :active')
                ->setParameter('active', $dto->getActive());
        }

        $aliasItems = AliasInterface::ITEMS;
        $builder
            ->leftJoin($alias.'.items', $aliasItems)
            ->addSelect($aliasItems);

        if ($dto->hasItemApiDto()) {
            if ($dto->getItemApiDto()->hasId()) {
                $builder->andWhere($aliasItems.'.id = :idItem')
                    ->setParameter('idItem', $dto->getItemApiDto()->getId());
            }
        }
    }
}
