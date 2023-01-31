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

namespace Evrinoma\NomenclatureBundle\Mediator\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface CommandMediatorInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     * @param ItemInterface       $entity
     *
     * @return ItemInterface
     *
     * @throws ItemCannotBeSavedException
     */
    public function onUpdate(ItemApiDtoInterface $dto, ItemInterface $entity): ItemInterface;

    /**
     * @param ItemApiDtoInterface $dto
     * @param ItemInterface       $entity
     *
     * @throws ItemCannotBeRemovedException
     */
    public function onDelete(ItemApiDtoInterface $dto, ItemInterface $entity): void;

    /**
     * @param ItemApiDtoInterface $dto
     * @param ItemInterface       $entity
     *
     * @return ItemInterface
     *
     * @throws ItemCannotBeSavedException
     * @throws ItemCannotBeCreatedException
     */
    public function onCreate(ItemApiDtoInterface $dto, ItemInterface $entity): ItemInterface;
}
