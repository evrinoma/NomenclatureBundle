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

namespace Evrinoma\NomenclatureBundle\Serializer;

interface GroupInterface
{
    public const API_POST_NOMENCLATURE = 'API_POST_NOMENCLATURE';
    public const API_PUT_NOMENCLATURE = 'API_PUT_NOMENCLATURE';
    public const API_GET_NOMENCLATURE = 'API_GET_NOMENCLATURE';
    public const API_CRITERIA_NOMENCLATURE = self::API_GET_NOMENCLATURE;
    public const APP_GET_BASIC_NOMENCLATURE = 'APP_GET_BASIC_NOMENCLATURE';

    public const API_POST_NOMENCLATURE_ITEM = 'API_POST_NOMENCLATURE_ITEM';
    public const API_PUT_NOMENCLATURE_ITEM = 'API_PUT_NOMENCLATURE_ITEM';
    public const API_GET_NOMENCLATURE_ITEM = 'API_GET_NOMENCLATURE_ITEM';
    public const API_CRITERIA_NOMENCLATURE_ITEM = self::API_GET_NOMENCLATURE_ITEM;
    public const APP_GET_BASIC_NOMENCLATURE_ITEM = 'APP_GET_BASIC_NOMENCLATURE_ITEM';
}
