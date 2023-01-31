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

namespace Evrinoma\NomenclatureBundle\Repository\Nomenclature;

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureProxyException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface NomenclatureQueryRepositoryInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return array
     *
     * @throws NomenclatureNotFoundException
     */
    public function findByCriteria(NomenclatureApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): NomenclatureInterface;

    /**
     * @param string $id
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureProxyException
     * @throws ORMException
     */
    public function proxy(string $id): NomenclatureInterface;
}
