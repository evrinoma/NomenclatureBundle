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

namespace Evrinoma\NomenclatureBundle\PreValidator\Item;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemInvalidException;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkNomenclature($dto)
            ->checkVendor($dto)
            ->checkStandard($dto)
            ->checkPosition($dto)
        ;
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkNomenclature($dto)
            ->checkId($dto)
            ->checkPosition($dto)
        ->checkVendor($dto)

            ->checkStandard($dto)
            ->checkActive($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkNomenclature(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasNomenclatureApiDto()) {
            throw new ItemInvalidException('The Dto has\'t nomenclature');
        }

        return $this;
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new ItemInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new ItemInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new ItemInvalidException('The Dto has\'t position');
        }

        return $this;
    }

    private function checkVendor(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasVendor()) {
            throw new ItemInvalidException('The Dto has\'t vendor');
        }

        return $this;
    }

    private function checkStandard(DtoInterface $dto): self
    {
        /** @var ItemApiDtoInterface $dto */
        if (!$dto->hasStandard()) {
            throw new ItemInvalidException('The Dto has\'t standard');
        }

        return $this;
    }
}
