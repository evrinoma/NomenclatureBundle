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
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface CommandManagerInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureInvalidException
     */
    public function post(NomenclatureApiDtoInterface $dto): NomenclatureInterface;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureInvalidException
     * @throws NomenclatureNotFoundException
     */
    public function put(NomenclatureApiDtoInterface $dto): NomenclatureInterface;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @throws NomenclatureCannotBeRemovedException
     * @throws NomenclatureNotFoundException
     */
    public function delete(NomenclatureApiDtoInterface $dto): void;
}
