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

namespace Evrinoma\NomenclatureBundle\Repository\Item;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemProxyException;
use Evrinoma\NomenclatureBundle\Mediator\Item\QueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

trait ItemRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param ItemInterface $item
     *
     * @return bool
     *
     * @throws ItemCannotBeSavedException
     * @throws ORMException
     */
    public function save(ItemInterface $item): bool
    {
        try {
            $this->persistWrapped($item);
        } catch (ORMInvalidArgumentException $e) {
            throw new ItemCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param ItemInterface $item
     *
     * @return bool
     */
    public function remove(ItemInterface $item): bool
    {
        return true;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function findByCriteria(ItemApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $items = $this->mediator->getResult($dto, $builder);

        if (0 === \count($items)) {
            throw new ItemNotFoundException('Cannot find file by findByCriteria');
        }

        return $items;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws ItemNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): ItemInterface
    {
        /** @var ItemInterface $item */
        $item = $this->findWrapped($id);

        if (null === $item) {
            throw new ItemNotFoundException("Cannot find file with id $id");
        }

        return $item;
    }

    /**
     * @param string $id
     *
     * @return ItemInterface
     *
     * @throws ItemProxyException
     * @throws ORMException
     */
    public function proxy(string $id): ItemInterface
    {
        $item = $this->referenceWrapped($id);

        if (!$this->containsWrapped($item)) {
            throw new ItemProxyException("Proxy doesn't exist with $id");
        }

        return $item;
    }
}
