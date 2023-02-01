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

namespace Evrinoma\NomenclatureBundle\Repository;

interface AliasInterface
{
    public const NOMENCLATURE = 'contact';
    public const NOMENCLATURES = AliasInterface::NOMENCLATURE.'s';

    public const ITEM = 'item';
    public const ITEMS = AliasInterface::ITEM.'s';
}
