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

namespace Evrinoma\NomenclatureBundle\Manager\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemProxyException;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\NomenclatureBundle\Repository\Item\ItemQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private ItemQueryRepositoryInterface $repository;

    public function __construct(ItemQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function criteria(ItemApiDtoInterface $dto): array
    {
        try {
            $nomenclature = $this->repository->findByCriteria($dto);
        } catch (ItemNotFoundException $e) {
            throw $e;
        }

        return $nomenclature;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemProxyException
     */
    public function proxy(ItemApiDtoInterface $dto): ItemInterface
    {
        try {
            if ($dto->hasId()) {
                $nomenclature = $this->repository->proxy($dto->idToString());
            } else {
                throw new ItemProxyException('Id value is not set while trying get proxy object');
            }
        } catch (ItemProxyException $e) {
            throw $e;
        }

        return $nomenclature;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemNotFoundException
     */
    public function get(ItemApiDtoInterface $dto): ItemInterface
    {
        try {
            $nomenclature = $this->repository->find($dto->idToString());
        } catch (ItemNotFoundException $e) {
            throw $e;
        }

        return $nomenclature;
    }
}
