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
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface as BaseNomenclatureApiDtoInterface;

interface NomenclatureApiDtoInterface
{
    public function setNomenclatureApiDto(BaseNomenclatureApiDtoInterface $nomenclatureApiDto): DtoInterface;
}
