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

namespace Evrinoma\NomenclatureBundle\Mediator\Item\Orm;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Mediator\Item\QueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Repository\AliasInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;
use Evrinoma\UtilsBundle\Mediator\Orm\QueryMediatorTrait;
use Evrinoma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
    use QueryMediatorTrait;

    protected static string $alias = AliasInterface::FILE;

    /**
     * @param DtoInterface          $dto
     * @param QueryBuilderInterface $builder
     *
     * @return mixed
     */
    public function createQuery(DtoInterface $dto, QueryBuilderInterface $builder): void
    {
        $alias = $this->alias();

        /** @var $dto ItemApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias.'.id = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias.'.active = :active')
                ->setParameter('active', $dto->getActive());
        }

        if ($dto->hasVendor()) {
            $builder
                ->andWhere($alias.'.vendor like :vendor')
                ->setParameter('vendor', '%'.$dto->getVendor().'%');
        }

        if ($dto->hasStandard()) {
            $builder
                ->andWhere($alias.'.standard like :standard')
                ->setParameter('standard', '%'.$dto->getStandard().'%');
        }

        $aliasNomenclature = AliasInterface::NOMENCLATURE;
        $builder
            ->leftJoin($alias.'.nomenclature', $aliasNomenclature)
            ->addSelect($aliasNomenclature);

        if ($dto->hasNomenclatureApiDto()) {
            if ($dto->getNomenclatureApiDto()->hasId()) {
                $builder->andWhere($aliasNomenclature.'.id = :idNomenclature')
                    ->setParameter('idNomenclature', $dto->getNomenclatureApiDto()->getId());
            }
        }
    }
}
