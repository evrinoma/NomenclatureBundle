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
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemProxyException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface QueryManagerInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function criteria(ItemApiDtoInterface $dto): array;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemNotFoundException
     */
    public function get(ItemApiDtoInterface $dto): ItemInterface;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemProxyException
     */
    public function proxy(ItemApiDtoInterface $dto): ItemInterface;
}
