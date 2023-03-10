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

namespace Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Preserve;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;

trait NomenclaturesApiDtoTrait
{
    public function addNomenclaturesApiDto(NomenclatureApiDtoInterface $nomenclaturesApiDto): DtoInterface
    {
        return parent::addNomenclaturesApiDto($nomenclaturesApiDto);
    }
}
