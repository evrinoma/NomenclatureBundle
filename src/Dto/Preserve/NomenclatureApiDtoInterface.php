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

namespace Evrinoma\NomenclatureBundle\Dto\Preserve;

use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\DescriptionInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\ItemsApiDtoInterface;

interface NomenclatureApiDtoInterface extends IdInterface, TitleInterface, PositionInterface, ActiveInterface, DescriptionInterface, ItemApiDtoInterface, ItemsApiDtoInterface
{
}
