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
use Symfony\Component\HttpFoundation\Request;

trait NomenclaturesApiDtoTrait
{
    protected array $nomenclaturesApiDto = [];

    protected static string $classNomenclaturesApiDto = NomenclatureApiDto::class;

    public function hasNomenclaturesApiDto(): bool
    {
        return 0 !== \count($this->nomenclaturesApiDto);
    }

    public function getNomenclaturesApiDto(): array
    {
        return $this->nomenclaturesApiDto;
    }

    public function genRequestNomenclaturesApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $entities = $request->get(NomenclaturesApiDtoInterface::NOMENCLATURES);
            if ($entities) {
                foreach ($entities as $entity) {
                    $newRequest = $this->getCloneRequest();
                    $entity[DtoInterface::DTO_CLASS] = static::$classNomenclaturesApiDto;
                    $newRequest->request->add($entity);

                    yield $newRequest;
                }
            }
        }
    }
}
