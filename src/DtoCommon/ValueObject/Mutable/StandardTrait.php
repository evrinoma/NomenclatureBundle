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

namespace Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable;

use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable\StandardTrait as StandardImmutableTrait;

trait StandardTrait
{
    use StandardImmutableTrait;

    /**
     * @param string $standard
     *
     * @return DtoInterface
     */
    protected function setStandard(string $standard): DtoInterface
    {
        $this->standard = trim($standard);

        return $this;
    }
}
