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
     *     joinColumns={@ORM\JoinColumn(name="nomenclature_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")}
     * )
     */
    protected $item;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    /**
     * @return Collection|ItemInterface[]
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    /**
     * @param Collection|ItemInterface[] $item
     *
     *  @return NomenclatureInterface
     */
    public function setItem($item): NomenclatureInterface
    {
        $this->item = $item;

        return $this;
    }
}
