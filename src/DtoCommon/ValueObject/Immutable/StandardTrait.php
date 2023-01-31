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

namespace Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Immutable;

trait StandardTrait
{
    private string $standard = '';

    public function hasStandard(): bool
    {
        return '' !== $this->standard;
    }

    /**
     * @return string
     */
    public function getStandard(): string
    {
        return $this->standard;
    }
}
