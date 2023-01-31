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

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Tests\Functional\Helper\BaseNomenclatureTestTrait;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Nomenclature\Active;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Nomenclature\Description;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Nomenclature\Id;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Nomenclature\Position;
use Evrinoma\NomenclatureBundle\Tests\Functional\ValueObject\Nomenclature\Title;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseNomenclature extends AbstractServiceTest implements BaseNomenclatureTestInterface
{
    use BaseNomenclatureTestTrait;

    public const API_GET = 'evrinoma/api/nomenclature';
    public const API_CRITERIA = 'evrinoma/api/nomenclature/criteria';
    public const API_DELETE = 'evrinoma/api/nomenclature/delete';
    public const API_PUT = 'evrinoma/api/nomenclature/save';
    public const API_POST = 'evrinoma/api/nomenclature/create';

    protected static function getDtoClass(): string
    {
        return NomenclatureApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            NomenclatureApiDtoInterface::ID => Id::value(),
            NomenclatureApiDtoInterface::TITLE => Title::default(),
            NomenclatureApiDtoInterface::POSITION => Position::value(),
            NomenclatureApiDtoInterface::ACTIVE => Active::value(),
            NomenclatureApiDtoInterface::DESCRIPTION => Description::default(),
        ];
    }

    public function actionPost(): void
    {
        $this->createNomenclature();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            NomenclatureApiDtoInterface::ACTIVE => Active::wrong(),
        ]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(), NomenclatureApiDtoInterface::ID => Id::value(), NomenclatureApiDtoInterface::ACTIVE => Active::block(), NomenclatureApiDtoInterface::TITLE => Title::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            NomenclatureApiDtoInterface::ACTIVE => Active::value(),
            NomenclatureApiDtoInterface::ID => Id::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            NomenclatureApiDtoInterface::ACTIVE => Active::delete(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([
            NomenclatureApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            NomenclatureApiDtoInterface::ACTIVE => Active::delete(),
            NomenclatureApiDtoInterface::TITLE => Title::value(),
        ]);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $query = static::getDefault([
            NomenclatureApiDtoInterface::ID => Id::value(),
            NomenclatureApiDtoInterface::TITLE => Title::value(),
            NomenclatureApiDtoInterface::POSITION => Position::value(),
            NomenclatureApiDtoInterface::DESCRIPTION => Description::value(),
        ]);

        $find = $this->assertGet(Id::value());

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
            NomenclatureApiDtoInterface::ID => Id::wrong(),
            NomenclatureApiDtoInterface::TITLE => Title::wrong(),
            NomenclatureApiDtoInterface::POSITION => Position::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createNomenclature();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([
            NomenclatureApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ID],
            NomenclatureApiDtoInterface::TITLE => Title::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            NomenclatureApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ID],
            NomenclatureApiDtoInterface::POSITION => Position::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([
            NomenclatureApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][NomenclatureApiDtoInterface::ID],
            NomenclatureApiDtoInterface::DESCRIPTION => Description::blank(),
        ]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createNomenclature();
        $this->testResponseStatusCreated();
        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankTitle();
        $this->testResponseStatusUnprocessable();
    }
}
