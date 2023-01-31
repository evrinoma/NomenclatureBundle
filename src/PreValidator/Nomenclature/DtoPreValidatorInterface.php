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

namespace Evrinoma\NomenclatureBundle\PreValidator\Nomenclature;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @throws NomenclatureInvalidException
     */
    public function onPost(NomenclatureApiDtoInterface $dto): void;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @throws NomenclatureInvalidException
     */
    public function onPut(NomenclatureApiDtoInterface $dto): void;

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @throws NomenclatureInvalidException
     */
    public function onDelete(NomenclatureApiDtoInterface $dto): void;
}
