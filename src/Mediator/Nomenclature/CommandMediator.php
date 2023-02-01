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

namespace Evrinoma\NomenclatureBundle\Mediator\Nomenclature;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Manager\Item\QueryManagerInterface as ItemQueryManagerInterface;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private ItemQueryManagerInterface $itemQueryManager;

    public function __construct(ItemQueryManagerInterface $itemQueryManager)
    {
        $this->itemQueryManager = $itemQueryManager;
    }

    public function onUpdate(DtoInterface $dto, $entity): NomenclatureInterface
    {
        /* @var $dto NomenclatureApiDtoInterface */
        $entity
            ->setDescription($dto->getDescription())
            ->setTitle($dto->getTitle())
            ->setPosition($dto->getPosition())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());

        if ($dto->hasItemsApiDto()) {
            try {
                foreach ($entity->getItems() as $item) {
                    $entity->removeItem($item);
                }

                foreach ($dto->getItemsApiDto() as $itemApiDto) {
                    $entity->addItem($this->itemQueryManager->proxy($itemApiDto));
                }
            } catch (\Exception $e) {
                throw new NomenclatureCannotBeSavedException($e->getMessage());
            }
        }

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        try {
            foreach ($entity->getItems() as $item) {
                $entity->removeItem($item);
            }
        } catch (\Exception $e) {
            throw new NomenclatureCannotBeRemovedException($e->getMessage());
        }
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): NomenclatureInterface
    {
        /* @var $dto NomenclatureApiDtoInterface */
        $entity
            ->setDescription($dto->getDescription())
            ->setTitle($dto->getTitle())
            ->setPosition($dto->getPosition())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActiveToActive();

        if ($dto->hasItemsApiDto()) {
            try {
                foreach ($entity->getItems() as $item) {
                    $entity->removeItem($item);
                }

                foreach ($dto->getItemsApiDto() as $itemApiDto) {
                    $entity->addItem($this->itemQueryManager->proxy($itemApiDto));
                }
            } catch (\Exception $e) {
                throw new NomenclatureCannotBeCreatedException($e->getMessage());
            }
        }

        return $entity;
    }
}
