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

namespace Evrinoma\NomenclatureBundle\Tests\Functional\Action;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDto;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Tests\Functional\Helper\BaseItemTestTrait;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Active;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Attachment;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Attributes;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Id;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Image;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Position;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Preview;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Standard;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Item\Vendor;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\TestUtilsBundle\Browser\ApiBrowserTestInterface;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseItem extends AbstractServiceTest implements BaseItemTestInterface
{
    use BaseItemTestTrait;

    public const API_GET = 'evrinoma/api/nomenclature/item';
    public const API_CRITERIA = 'evrinoma/api/nomenclature/item/criteria';
    public const API_DELETE = 'evrinoma/api/nomenclature/item/delete';
    public const API_PUT = 'evrinoma/api/nomenclature/item/save';
    public const API_POST = 'evrinoma/api/nomenclature/item/create';

    protected string $methodPut = ApiBrowserTestInterface::POST;

    protected static array $header = ['CONTENT_TYPE' => 'multipart/form-data'];
    protected bool $form = true;

    protected static function getDtoClass(): string
    {
        return ItemApiDto::class;
    }

    protected static function defaultData(): array
    {
        static::initFiles();

        return [
            ItemApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ItemApiDtoInterface::ID => Id::value(),
            ItemApiDtoInterface::STANDARD => Standard::default(),
            ItemApiDtoInterface::VENDOR => Vendor::default(),
            ItemApiDtoInterface::ATTRIBUTES => Attributes::default(),
            ItemApiDtoInterface::POSITION => Position::default(),
            ItemApiDtoInterface::ACTIVE => Active::value(),
            ItemApiDtoInterface::IMAGE => Image::default(),
            ItemApiDtoInterface::PREVIEW => Preview::default(),
            ItemApiDtoInterface::ATTACHMENT => Attachment::default(),
            NomenclatureApiDtoInterface::NOMENCLATURE => BaseNomenclature::defaultData(),
        ];
    }

    public function actionPost(): void
    {
        $this->createItem();
        $this->testResponseStatusCreated();

        static::$files = [];
        $query = static::getDefault([
            ItemApiDtoInterface::STANDARD => str_shuffle(Standard::value()),
            ItemApiDtoInterface::VENDOR => str_shuffle(Vendor::value()),
        ]);

        $this->post($query);
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([
            ItemApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ItemApiDtoInterface::ACTIVE => Active::wrong(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([
            ItemApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ItemApiDtoInterface::ID => Id::value(),
            ItemApiDtoInterface::ACTIVE => Active::block(),
            ItemApiDtoInterface::VENDOR => Vendor::value(),
            ItemApiDtoInterface::STANDARD => Standard::value(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([
            ItemApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ItemApiDtoInterface::ACTIVE => Active::value(),
            ItemApiDtoInterface::ID => Id::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            ItemApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ItemApiDtoInterface::ACTIVE => Active::delete(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::getDefault([
            ItemApiDtoInterface::ID => Id::value(),
            ItemApiDtoInterface::STANDARD => Standard::value(),
        ]);

        $find = $this->assertGet(Id::value());

        $updated = $this->put($query);
        $this->testResponseStatusOK();

        $this->compareResults($find, $updated, $query);

        static::$files = [];

        $updated = $this->put($query);
        $this->testResponseStatusOK();

        $this->compareResults($find, $updated, $query);
    }

    public function actionGet(): void
    {
        $find = $this->assertGet(Id::value());
    }

    public function actionGetNotFound(): void
    {
        $response = $this->get(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteNotFound(): void
    {
        $response = $this->delete(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteUnprocessable(): void
    {
        $response = $this->delete(Id::blank());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPutNotFound(): void
    {
        $this->put(static::getDefault([
            ItemApiDtoInterface::ID => Id::wrong(),
            ItemApiDtoInterface::STANDARD => Standard::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createItem();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([
            ItemApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ID],
            ItemApiDtoInterface::STANDARD => Standard::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            ItemApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ID],
            ItemApiDtoInterface::POSITION => Position::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $created = $this->createItem();
        $this->testResponseStatusCreated();

        static::$files = [];

        $this->createItem();
        $this->testResponseStatusConflict();

        $query = static::getDefault([
            ItemApiDtoInterface::IMAGE => null,
            ItemApiDtoInterface::PREVIEW => null,
            ItemApiDtoInterface::ATTACHMENT => null,
            ]);

        $this->post($query);
        $this->testResponseStatusConflict();

        $query = static::getDefault([
            ItemApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ItemApiDtoInterface::ID],
            ItemApiDtoInterface::VENDOR => Vendor::value(),
            ItemApiDtoInterface::STANDARD => Standard::value(),
            ]);

        $this->put($query);
        $this->testResponseStatusConflict();
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankStandard();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankVendor();
        $this->testResponseStatusUnprocessable();
    }
}
