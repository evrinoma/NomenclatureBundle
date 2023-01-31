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

namespace Evrinoma\NomenclatureBundle\Repository\Item;

use Doctrine\ORM\Exception\ORMException;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemProxyException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface ItemQueryRepositoryInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function findByCriteria(ItemApiDtoInterface $dto): array;

    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     *
     * @return ItemInterface
     *
     * @throws ItemNotFoundException
     */
    public function find(string $id, $lockMode = null, $lockVersion = null): ItemInterface;

    /**
     * @param string $id
     *
     * @return ItemInterface
     *
     * @throws ItemProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ItemInterface;
}
