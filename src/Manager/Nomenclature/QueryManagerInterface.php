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

namespace Evrinoma\NomenclatureBundle\Manager\Nomenclature;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureProxyException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface QueryManagerInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return array
     *
     * @throws NomenclatureNotFoundException
     */
    public function criteria(NomenclatureApiDtoInterface $dto): array;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureNotFoundException
     */
    public function get(NomenclatureApiDtoInterface $dto): NomenclatureInterface;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureProxyException
     */
    public function proxy(NomenclatureApiDtoInterface $dto): NomenclatureInterface;
}
