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

use Doctrine\Common\Collections\Collection;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtInterface;
use Evrinoma\UtilsBundle\Entity\DescriptionInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;
use Evrinoma\UtilsBundle\Entity\PositionInterface;
use Evrinoma\UtilsBundle\Entity\TitleInterface;

interface NomenclatureInterface extends ActiveInterface, CreateUpdateAtInterface, IdInterface, TitleInterface, PositionInterface, DescriptionInterface
{
    /**
     * @param Collection|ItemInterface[] $item
     *
     *  @return NomenclatureInterface
     */
    public function setItem($item): NomenclatureInterface;

    /**
     * @return Collection|ItemInterface[]
     */
    public function getItem(): Collection;
}
