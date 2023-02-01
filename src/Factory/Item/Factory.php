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

namespace Evrinoma\NomenclatureBundle\Factory\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Entity\Item\BaseItem;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;

class Factory implements FactoryInterface
{
    private static string $entityClass = BaseItem::class;

    public function __construct(string $entityClass)
    {
        self::$entityClass = $entityClass;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     */
    public function create(ItemApiDtoInterface $dto): ItemInterface
    {
        /* @var BaseItem $nomenclature */
        return new self::$entityClass();
    }
}
