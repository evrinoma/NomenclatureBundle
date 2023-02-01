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
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface as BaseItemApiDtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait ItemApiDtoTrait
{
    protected ?BaseItemApiDtoInterface $itemApiDto = null;

    protected static string $classItemApiDto = ItemApiDto::class;

    public function genRequestItemApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $item = $request->get(ItemApiDtoInterface::ITEM);
            if ($item) {
                $newRequest = $this->getCloneRequest();
                $item[DtoInterface::DTO_CLASS] = static::$classItemApiDto;
                $newRequest->request->add($item);

                yield $newRequest;
            }
        }
    }

    public function hasItemApiDto(): bool
    {
        return null !== $this->itemApiDto;
    }

    public function getItemApiDto(): BaseItemApiDtoInterface
    {
        return $this->itemApiDto;
    }
}
