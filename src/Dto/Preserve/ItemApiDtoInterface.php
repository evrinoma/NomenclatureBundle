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
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\PreviewInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\AttributesInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\NomenclaturesApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\StandardInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\VendorInterface;

interface ItemApiDtoInterface extends IdInterface, ActiveInterface, PositionInterface, NomenclatureApiDtoInterface, NomenclaturesApiDtoInterface, AttachmentInterface, ImageInterface, PreviewInterface, AttributesInterface, VendorInterface, StandardInterface
{
}
