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

namespace Evrinoma\NomenclatureBundle\Factory\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

interface FactoryInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     */
    public function create(ItemApiDtoInterface $dto): ItemInterface;
}
