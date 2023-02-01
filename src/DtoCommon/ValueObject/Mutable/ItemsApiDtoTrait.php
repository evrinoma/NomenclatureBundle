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

namespace Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\ItemsApiDtoTrait as ItemsApiDtoImmutableTrait;

trait ItemsApiDtoTrait
{
    use ItemsApiDtoImmutableTrait;

    public function addItemsApiDto(ItemApiDtoInterface $itemsApiDto): DtoInterface
    {
        $this->itemsApiDto[] = $itemsApiDto;

        return $this;
    }
}
