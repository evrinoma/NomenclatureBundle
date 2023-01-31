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

namespace Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item;

class Attributes extends AbstractArray
{
    protected static array $value = ['D' => 'd', 'E' => 'e', 'F' => 'f'];
    protected static array $default = ['d' => 'D', 'e' => 'E', 'f' => 'F'];
}
