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

namespace Evrinoma\NomenclatureBundle\Tests\Functional\Helper;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseNomenclatureTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createNomenclature(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ID]);
        Assert::assertEquals($query[NomenclatureApiDtoInterface::TITLE], $entity[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::TITLE]);
        Assert::assertEquals($query[NomenclatureApiDtoInterface::DESCRIPTION], $entity[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::DESCRIPTION]);
        Assert::assertEquals($query[NomenclatureApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::POSITION]);
    }

    protected function createConstraintBlankTitle(): array
    {
        $query = static::getDefault([NomenclatureApiDtoInterface::TITLE => '']);

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkNomenclature($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkNomenclature($entity): void
    {
        Assert::assertArrayHasKey(NomenclatureApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(NomenclatureApiDtoInterface::TITLE, $entity);
        Assert::assertArrayHasKey(NomenclatureApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(NomenclatureApiDtoInterface::POSITION, $entity);
        Assert::assertArrayHasKey(NomenclatureApiDtoInterface::DESCRIPTION, $entity);
    }
}
