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

namespace Evrinoma\NomenclatureBundle\Factory\Nomenclature;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface FactoryInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     */
    public function create(NomenclatureApiDtoInterface $dto): NomenclatureInterface;
}
