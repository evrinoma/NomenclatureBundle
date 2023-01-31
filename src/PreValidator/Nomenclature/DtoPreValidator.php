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

namespace Evrinoma\NomenclatureBundle\PreValidator\Nomenclature;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureInvalidException;
use Evrinoma\UtilsBundle\PreValidator\AbstractPreValidator;

class DtoPreValidator extends AbstractPreValidator implements DtoPreValidatorInterface
{
    public function onPost(DtoInterface $dto): void
    {
        $this
            ->checkTitle($dto)
            ->checkDescription($dto)
            ->checkPosition($dto)
            ->checkNomenclature($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        $this
            ->checkId($dto)
            ->checkTitle($dto)
            ->checkDescription($dto)
            ->checkActive($dto)
            ->checkPosition($dto)
            ->checkNomenclature($dto);
    }

    public function onDelete(DtoInterface $dto): void
    {
        $this
            ->checkId($dto);
    }

    private function checkDescription(DtoInterface $dto): self
    {
        /** @var NomenclatureApiDtoInterface $dto */
        if (!$dto->hasDescription()) {
            throw new NomenclatureInvalidException('The Dto has\'t description');
        }

        return $this;
    }

    private function checkPosition(DtoInterface $dto): self
    {
        /** @var NomenclatureApiDtoInterface $dto */
        if (!$dto->hasPosition()) {
            throw new NomenclatureInvalidException('The Dto has\'t position');
        }

        return $this;
    }

    private function checkTitle(DtoInterface $dto): self
    {
        /** @var NomenclatureApiDtoInterface $dto */
        if (!$dto->hasTitle()) {
            throw new NomenclatureInvalidException('The Dto has\'t title');
        }

        return $this;
    }

    private function checkActive(DtoInterface $dto): self
    {
        /** @var NomenclatureApiDtoInterface $dto */
        if (!$dto->hasActive()) {
            throw new NomenclatureInvalidException('The Dto has\'t active');
        }

        return $this;
    }

    private function checkNomenclature(DtoInterface $dto): self
    {
        /* @var NomenclatureApiDtoInterface $dto */

        return $this;
    }

    private function checkId(DtoInterface $dto): self
    {
        /** @var NomenclatureApiDtoInterface $dto */
        if (!$dto->hasId()) {
            throw new NomenclatureInvalidException('The Dto has\'t ID or class invalid');
        }

        return $this;
    }
}
