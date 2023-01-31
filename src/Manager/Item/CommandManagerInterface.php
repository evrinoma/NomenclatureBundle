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

namespace Evrinoma\NomenclatureBundle\Manager\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface CommandManagerInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemInvalidException
     */
    public function post(ItemApiDtoInterface $dto): ItemInterface;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemInvalidException
     * @throws ItemNotFoundException
     */
    public function put(ItemApiDtoInterface $dto): ItemInterface;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @throws ItemCannotBeRemovedException
     * @throws ItemNotFoundException
     */
    public function delete(ItemApiDtoInterface $dto): void;
}
