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

namespace Evrinoma\NomenclatureBundle\Mediator\Nomenclature;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

interface CommandMediatorInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     * @param NomenclatureInterface       $entity
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureCannotBeSavedException
     */
    public function onUpdate(NomenclatureApiDtoInterface $dto, NomenclatureInterface $entity): NomenclatureInterface;

    /**
     * @param NomenclatureApiDtoInterface $dto
     * @param NomenclatureInterface       $entity
     *
     * @throws NomenclatureCannotBeRemovedException
     */
    public function onDelete(NomenclatureApiDtoInterface $dto, NomenclatureInterface $entity): void;

    /**
     * @param NomenclatureApiDtoInterface $dto
     * @param NomenclatureInterface       $entity
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureCannotBeSavedException
     * @throws NomenclatureCannotBeCreatedException
     */
    public function onCreate(NomenclatureApiDtoInterface $dto, NomenclatureInterface $entity): NomenclatureInterface;
}
