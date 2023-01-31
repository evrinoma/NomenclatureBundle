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

namespace Evrinoma\NomenclatureBundle\Repository\Nomenclature;

use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface NomenclatureCommandRepositoryInterface
{
    /**
     * @param NomenclatureInterface $nomenclature
     *
     * @return bool
     *
     * @throws NomenclatureCannotBeSavedException
     */
    public function save(NomenclatureInterface $nomenclature): bool;

    /**
     * @param NomenclatureInterface $nomenclature
     *
     * @return bool
     *
     * @throws NomenclatureCannotBeRemovedException
     */
    public function remove(NomenclatureInterface $nomenclature): bool;
}
