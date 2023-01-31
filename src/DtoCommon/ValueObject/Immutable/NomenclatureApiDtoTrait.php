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

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait NomenclatureApiDtoTrait
{
    protected ?NomenclatureApiDtoInterface $nomenclatureApiDto = null;

    protected static string $classNomenclatureApiDto = NomenclatureApiDto::class;

    public function genRequestNomenclatureApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $nomenclature = $request->get(NomenclatureApiDtoInterface::NOMENCLATURE);
            if ($nomenclature) {
                $newRequest = $this->getCloneRequest();
                $nomenclature[DtoInterface::DTO_CLASS] = static::$classNomenclatureApiDto;
                $newRequest->request->add($nomenclature);

                yield $newRequest;
            }
        }
    }

    public function hasNomenclatureApiDto(): bool
    {
        return null !== $this->nomenclatureApiDto;
    }

    public function getNomenclatureApiDto(): NomenclatureApiDtoInterface
    {
        return $this->nomenclatureApiDto;
    }
}
