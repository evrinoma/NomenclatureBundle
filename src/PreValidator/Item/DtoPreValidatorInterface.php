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

namespace Evrinoma\NomenclatureBundle\PreValidator\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param ItemApiDtoInterface $dto
     *
     * @throws ItemInvalidException
     */
    public function onPost(ItemApiDtoInterface $dto): void;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @throws ItemInvalidException
     */
    public function onPut(ItemApiDtoInterface $dto): void;

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @throws ItemInvalidException
     */
    public function onDelete(ItemApiDtoInterface $dto): void;
}
