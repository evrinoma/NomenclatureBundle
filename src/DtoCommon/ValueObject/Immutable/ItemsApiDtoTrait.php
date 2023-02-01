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
use Evrinoma\NomenclatureBundle\Dto\ItemApiDto;
use Symfony\Component\HttpFoundation\Request;

trait ItemsApiDtoTrait
{
    protected array $itemsApiDto = [];

    protected static string $classItemsApiDto = ItemApiDto::class;

    public function hasItemsApiDto(): bool
    {
        return 0 !== \count($this->itemsApiDto);
    }

    public function getItemsApiDto(): array
    {
        return $this->itemsApiDto;
    }

    public function genRequestItemsApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $entities = $request->get(ItemsApiDtoInterface::ITEMS);
            if ($entities) {
                foreach ($entities as $entity) {
                    $newRequest = $this->getCloneRequest();
                    $entity[DtoInterface::DTO_CLASS] = static::$classItemsApiDto;
                    $newRequest->request->add($entity);

                    yield $newRequest;
                }
            }
        }
    }
}
