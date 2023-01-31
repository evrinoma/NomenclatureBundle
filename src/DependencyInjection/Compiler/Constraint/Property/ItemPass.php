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

namespace Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Property;

use Evrinoma\NomenclatureBundle\Validator\ItemValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ItemPass extends AbstractConstraint implements CompilerPassInterface
{
    public const ITEM_CONSTRAINT = 'evrinoma.nomenclature.constraint.item.property';

    protected static string $alias = self::ITEM_CONSTRAINT;
    protected static string $class = ItemValidator::class;
    protected static string $methodCall = 'addPropertyConstraint';
}
