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

use Evrinoma\DtoCommon\ValueObject\Preserve\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Preserve\PreviewTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve\AttributesTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve\NomenclatureApiDtoTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve\NomenclaturesApiDtoTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve\StandardTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve\VendorTrait;

trait ItemApiDtoTrait
{
    use ActiveTrait;
    use AttachmentTrait;
    use AttributesTrait;
    use IdTrait;
    use ImageTrait;
    use NomenclatureApiDtoTrait;
    use NomenclaturesApiDtoTrait;
    use PositionTrait;
    use PreviewTrait;
    use StandardTrait;
    use VendorTrait;
}
