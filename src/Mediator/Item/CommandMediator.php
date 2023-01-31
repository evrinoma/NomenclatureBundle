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

namespace Evrinoma\NomenclatureBundle\Mediator\Item;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Manager\Nomenclature\QueryManagerInterface as NomenclatureQueryManagerInterface;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\NomenclatureBundle\System\FileSystemInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
    private FileSystemInterface $fileSystem;
    private NomenclatureQueryManagerInterface $nomenclatureQueryManager;

    public function __construct(FileSystemInterface $itemSystem, NomenclatureQueryManagerInterface $nomenclatureQueryManager)
    {
        $this->fileSystem = $itemSystem;
        $this->nomenclatureQueryManager = $nomenclatureQueryManager;
    }

    public function onUpdate(DtoInterface $dto, $entity): ItemInterface
    {
        /* @var $dto ItemApiDtoInterface */
        try {
            $entity->setNomenclature($this->nomenclatureQueryManager->proxy($dto->getNomenclatureApiDto()));
        } catch (\Exception $e) {
            throw new ItemCannotBeSavedException($e->getMessage());
        }

        $entity
            ->setStandard($dto->getStandard())
            ->setVendor($dto->getVendor())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setAttributes($dto->getAttributes())
            ->setPosition($dto->getPosition())
            ->setActive($dto->getActive());

        if ($dto->hasImage()) {
            $itemImage = $this->fileSystem->save($dto->getImage());
            $entity->setAttachment($itemImage->getPathname());
        } else {
            $entity->resetAttachment();
        }

        if ($dto->hasPreview()) {
            $filePreview = $this->fileSystem->save($dto->getPreview());
            $entity->setPreview($filePreview->getPathname());
        } else {
            $entity->resetAttachment();
        }

        if ($dto->hasAttachment()) {
            $fileAttachment = $this->fileSystem->save($dto->getAttachment());
            $entity->setAttachment($fileAttachment->getPathname());
        } else {
            $entity->resetAttachment();
        }

        return $entity;
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {
        $entity
            ->setActiveToDelete();
    }

    public function onCreate(DtoInterface $dto, $entity): ItemInterface
    {
        /* @var $dto ItemApiDtoInterface */
        try {
            $entity->setNomenclature($this->nomenclatureQueryManager->proxy($dto->getNomenclatureApiDto()));
        } catch (\Exception $e) {
            throw new ItemCannotBeCreatedException($e->getMessage());
        }

        $entity
            ->setStandard($dto->getStandard())
            ->setVendor($dto->getVendor())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setAttributes($dto->getAttributes())
            ->setPosition($dto->getPosition())
            ->setActiveToActive();

        if ($dto->hasImage()) {
            $itemImage = $this->fileSystem->save($dto->getImage());
            $entity->setImage($itemImage->getPathname());
        } else {
            $entity->resetImage();
        }

        if ($dto->hasPreview()) {
            $filePreview = $this->fileSystem->save($dto->getPreview());
            $entity->setPreview($filePreview->getPathname());
        } else {
            $entity->resetPreview();
        }

        if ($dto->hasAttachment()) {
            $fileAttachment = $this->fileSystem->save($dto->getAttachment());
            $entity->setAttachment($fileAttachment->getPathname());
        } else {
            $entity->resetAttachment();
        }

        return $entity;
    }
}
