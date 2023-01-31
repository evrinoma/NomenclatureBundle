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

namespace Evrinoma\NomenclatureBundle\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\NomenclatureBundle\Model\Item\AbstractItem;

/**
 * @ORM\Table(name="e_nomenclature_item")
 * @ORM\Entity
 */
class BaseItem extends AbstractItem
{
}
