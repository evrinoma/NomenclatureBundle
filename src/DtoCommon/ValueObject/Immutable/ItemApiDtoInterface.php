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

namespace Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface as BaseItemApiDtoInterface;

interface ItemApiDtoInterface
{
    public const ITEM = BaseItemApiDtoInterface::ITEM;

    public function hasItemApiDto(): bool;

    public function getItemApiDto(): BaseItemApiDtoInterface;
}
