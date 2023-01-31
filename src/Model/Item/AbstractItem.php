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

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\AttachmentTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\ImageTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\PreviewTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="idx_item", columns={"vendor", "standard"})})
 */
abstract class AbstractItem implements ItemInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use CreateUpdateAtTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use PreviewTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255, nullable=false)
     */
    protected $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="standard", type="string", length=512, nullable=false)
     */
    protected $standard;

    /**
     * @var array|null
     *
     * @ORM\Column(name="attributes", type="json", nullable=true)
     */
    protected ?array $attributes = null;

    /**
     * @ORM\Column(name="image", type="string", length=2047, nullable=true)
     */
    protected $image = null;

    /**
     * @ORM\Column(name="preview", type="string", length=2047, nullable=true)
     */
    protected $preview = null;

    /**
     * @ORM\Column(name="attachment", type="string", length=2047, nullable=true)
     */
    protected $attachment = null;

    /**
     * @ORM\ManyToOne(targetEntity="Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface")
     * @ORM\JoinColumn(name="nomenclature_id", referencedColumnName="id")
     */
    protected ?NomenclatureInterface $nomenclature = null;

    public function resetAttachment(): ItemInterface
    {
        $this->attachment = null;

        return $this;
    }

    public function hasAttachment(): bool
    {
        return null !== $this->attachment;
    }

    public function resetPreview(): ItemInterface
    {
        $this->preview = null;

        return $this;
    }

    public function haspPreview(): bool
    {
        return null !== $this->preview;
    }

    public function resetImage(): ItemInterface
    {
        $this->image = null;

        return $this;
    }

    public function hasImage(): bool
    {
        return null !== $this->image;
    }

    public function getNomenclature(): NomenclatureInterface
    {
        return $this->nomenclature;
    }

    public function resetNomenclature(): ItemInterface
    {
        $this->nomenclature = null;

        return $this;
    }

    public function setNomenclature(NomenclatureInterface $nomenclature): ItemInterface
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasNomenclature(): bool
    {
        return null !== $this->nomenclature;
    }

    /**
     * @return string
     */
    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     *
     * @return self
     */
    public function setVendor(string $vendor): ItemInterface
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return string
     */
    public function getStandard(): ?string
    {
        return $this->standard;
    }

    /**
     * @param string $standard
     *
     * @return self
     */
    public function setStandard(string $standard): ItemInterface
    {
        $this->standard = $standard;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function toAttributes(): array
    {
        return $this->getAttributes() ? ['attributes' => $this->getAttributes()] : [];
    }

    public function setAttributes(array $attributes): ItemInterface
    {
        $this->attributes = $attributes;

        return $this;
    }
}
