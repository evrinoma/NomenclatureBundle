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
use Evrinoma\DtoCommon\ValueObject\Immutable\AttachmentInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ImageInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\PreviewInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\AttributesInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\NomenclaturesApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\StandardInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\VendorInterface;

interface ItemApiDtoInterface extends DtoInterface, IdInterface, ActiveInterface, NomenclatureApiDtoInterface, NomenclaturesApiDtoInterface, PositionInterface, AttachmentInterface, ImageInterface, PreviewInterface, AttributesInterface, VendorInterface, StandardInterface
{
    public const ITEM = 'item';
    public const ITEMS = ItemApiDtoInterface::ITEM.'s';
}
