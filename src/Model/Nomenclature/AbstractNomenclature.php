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

namespace Evrinoma\NomenclatureBundle\Model\Nomenclature;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\DescriptionTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;
use Evrinoma\UtilsBundle\Entity\PositionTrait;
use Evrinoma\UtilsBundle\Entity\TitleTrait;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractNomenclature implements NomenclatureInterface
{
    use ActiveTrait;
    use CreateUpdateAtTrait;
    use DescriptionTrait;
    use IdTrait;
    use PositionTrait;
    use TitleTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    protected $description;

    /**
     * @var ArrayCollection|ItemInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Evrinoma\NomenclatureBundle\Model\Item\ItemInterface")
     * @ORM\JoinTable(
     *     name="e_nomenclature_nomenclature_phones",
     *     joinColumns={@ORM\JoinColumn(name="nomenclature_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")}
     * )
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return Collection|ItemInterface[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ItemInterface $item): NomenclatureInterface
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(ItemInterface $item): NomenclatureInterface
    {
        $this->items->removeElement($item);

        return $this;
    }
}
