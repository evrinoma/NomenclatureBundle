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

namespace Evrinoma\NomenclatureBundle\Repository\Orm\Nomenclature;

use Doctrine\Persistence\ManagerRegistry;
use Evrinoma\NomenclatureBundle\Mediator\Nomenclature\QueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Repository\Nomenclature\NomenclatureRepositoryInterface;
use Evrinoma\NomenclatureBundle\Repository\Nomenclature\NomenclatureRepositoryTrait;
use Evrinoma\UtilsBundle\Repository\Orm\RepositoryWrapper;
use Evrinoma\UtilsBundle\Repository\RepositoryWrapperInterface;

class NomenclatureRepository extends RepositoryWrapper implements NomenclatureRepositoryInterface, RepositoryWrapperInterface
{
    use NomenclatureRepositoryTrait;

    /**
     * @param ManagerRegistry        $registry
     * @param string                 $entityClass
     * @param QueryMediatorInterface $mediator
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, QueryMediatorInterface $mediator)
    {
        parent::__construct($registry, $entityClass);
        $this->mediator = $mediator;
    }
}
