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

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait BaseItemTestTrait
{
    protected static function initFiles(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'http');

        file_put_contents($path, 'my_file');

        $fileImage = $fileAttachment = $filePreview = new UploadedFile($path, 'my_file');

        static::$files = [
            static::getDtoClass() => [
                ItemApiDtoInterface::IMAGE => $fileImage,
                ItemApiDtoInterface::PREVIEW => $filePreview,
                ItemApiDtoInterface::ATTACHMENT => $fileAttachment,
            ],
        ];
    }

    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createItem(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankStandard(): array
    {
        $query = static::getDefault([ItemApiDtoInterface::STANDARD => '']);

        return $this->post($query);
    }

    protected function createConstraintBlankVendor(): array
    {
        $query = static::getDefault([ItemApiDtoInterface::VENDOR => '']);

        return $this->post($query);
    }

    protected function compareResults(array $value, array $entity, array $query): void
    {
        Assert::assertEquals($value[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ID], $entity[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ID]);
        Assert::assertEquals($query[ItemApiDtoInterface::STANDARD], $entity[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::STANDARD]);
        Assert::assertEquals($query[ItemApiDtoInterface::VENDOR], $entity[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::VENDOR]);
        Assert::assertEquals($query[ItemApiDtoInterface::POSITION], $entity[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::POSITION]);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkNomenclatureItem($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkNomenclatureItem($entity): void
    {
        Assert::assertArrayHasKey(ItemApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::STANDARD, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::VENDOR, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::IMAGE, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::NOMENCLATURES, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::IMAGE, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::PREVIEW, $entity);
        Assert::assertArrayHasKey(ItemApiDtoInterface::ATTACHMENT, $entity);
    }
}
