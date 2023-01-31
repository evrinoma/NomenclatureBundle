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

use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface ItemCommandRepositoryInterface
{
    /**
     * @param ItemInterface $item
     *
     * @return bool
     *
     * @throws ItemCannotBeSavedException
     */
    public function save(ItemInterface $item): bool;

    /**
     * @param ItemInterface $item
     *
     * @return bool
     *
     * @throws ItemCannotBeRemovedException
     */
    public function remove(ItemInterface $item): bool;
}
