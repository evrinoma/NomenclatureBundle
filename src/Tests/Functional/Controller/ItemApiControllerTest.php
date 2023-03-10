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

namespace Evrinoma\NomenclatureBundle\Tests\Functional\Controller;

use Evrinoma\NomenclatureBundle\Fixtures\FixtureInterface;
use Evrinoma\TestUtilsBundle\Action\ActionTestInterface;
use Evrinoma\TestUtilsBundle\Functional\Orm\AbstractFunctionalTest;
use Psr\Container\ContainerInterface;

/**
 * @group functional
 */
final class ItemApiControllerTest extends AbstractFunctionalTest
{
    protected string $actionServiceName = 'evrinoma.nomenclature.test.functional.action.item';

    protected function getActionService(ContainerInterface $container): ActionTestInterface
    {
        return $container->get($this->actionServiceName);
    }

    public static function getFixtures(): array
    {
        return [
            FixtureInterface::NOMENCLATURE_ITEM_FIXTURES,
            FixtureInterface::NOMENCLATURE_FIXTURES,
        ];
    }
}
