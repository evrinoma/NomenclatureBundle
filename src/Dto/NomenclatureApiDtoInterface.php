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

namespace Evrinoma\NomenclatureBundle\Dto;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\DescriptionInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\TitleInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\ItemsApiDtoInterface;

interface NomenclatureApiDtoInterface extends DtoInterface, IdInterface, TitleInterface, PositionInterface, ActiveInterface, DescriptionInterface, ItemApiDtoInterface, ItemsApiDtoInterface
{
    public const NOMENCLATURE = 'nomenclature';
    public const NOMENCLATURES = NomenclatureApiDtoInterface::NOMENCLATURE.'s';
}
