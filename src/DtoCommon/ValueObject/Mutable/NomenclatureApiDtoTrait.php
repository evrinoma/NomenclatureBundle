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
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\NomenclatureApiDtoTrait as NomenclatureApiDtoImmutableTrait;

trait NomenclatureApiDtoTrait
{
    use NomenclatureApiDtoImmutableTrait;

    /**
     * @param NomenclatureApiDtoInterface $nomenclatureApiDto
     *
     * @return DtoInterface
     */
    public function setNomenclatureApiDto(NomenclatureApiDtoInterface $nomenclatureApiDto): DtoInterface
    {
        $this->nomenclatureApiDto = $nomenclatureApiDto;

        return $this;
    }
}
