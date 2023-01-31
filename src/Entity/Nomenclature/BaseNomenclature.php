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

namespace Evrinoma\NomenclatureBundle\Entity\Nomenclature;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\AbstractNomenclature;

/**
 * @ORM\Table(name="e_nomenclature_nomenclature")
 * @ORM\Entity
 */
class BaseNomenclature extends AbstractNomenclature
{
}
