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

namespace Evrinoma\NomenclatureBundle\Model\Item;

use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\AttachmentInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;
use Evrinoma\UtilsBundle\Entity\ImageInterface;
use Evrinoma\UtilsBundle\Entity\PositionInterface;
use Evrinoma\UtilsBundle\Entity\PreviewInterface;

interface ItemInterface extends ActiveInterface, IdInterface, CreateUpdateAtInterface, PreviewInterface, ImageInterface, AttachmentInterface, PositionInterface
{
    public function resetNomenclature(): ItemInterface;

    public function hasNomenclature(): bool;

    public function getNomenclature(): NomenclatureInterface;

    public function setNomenclature(NomenclatureInterface $nomenclature): ItemInterface;

    public function getVendor(): ?string;

    public function setVendor(string $vendor): ItemInterface;

    public function getStandard(): ?string;

    public function setStandard(string $standard): ItemInterface;

    public function getAttributes(): ?array;

    public function toAttributes(): array;

    public function setAttributes(array $attributes): ItemInterface;

    public function resetAttachment(): ItemInterface;

    public function hasAttachment(): bool;

    public function resetPreview(): ItemInterface;

    public function haspPreview(): bool;

    public function resetImage(): ItemInterface;

    public function hasImage(): bool;
}
