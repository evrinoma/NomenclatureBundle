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

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface as BaseNomenclatureApiDtoInterface;

interface NomenclaturesApiDtoInterface
{
    public const NOMENCLATURES = BaseNomenclatureApiDtoInterface::NOMENCLATURES;

    public function hasNomenclaturesApiDto(): bool;

    public function getNomenclaturesApiDto(): array;
}
