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

namespace Evrinoma\NomenclatureBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Entity\Item\BaseItem;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class ItemFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => 'xxxxyyyy',
            ItemApiDtoInterface::ACTIVE => 'a',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 1,
            NomenclatureApiDtoInterface::NOMENCLATURES => [1, 2],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
        ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '0',
            ItemApiDtoInterface::ACTIVE => 'a',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 2,
            NomenclatureApiDtoInterface::NOMENCLATURES => [2, 3],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
        ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '1',
            ItemApiDtoInterface::ACTIVE => 'a',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 3,
            NomenclatureApiDtoInterface::NOMENCLATURES => [3, 4],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
        ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '2',
            ItemApiDtoInterface::ACTIVE => 'd',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 4,
            NomenclatureApiDtoInterface::NOMENCLATURES => [5, 6],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
            ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '3',
            ItemApiDtoInterface::ACTIVE => 'b',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 5,
            NomenclatureApiDtoInterface::NOMENCLATURES => [0, 1],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
        ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '4',
            ItemApiDtoInterface::ACTIVE => 'd',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 6,
            NomenclatureApiDtoInterface::NOMENCLATURES => [2, 3],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
            ],
        [
            ItemApiDtoInterface::STANDARD => 'GOST',
            ItemApiDtoInterface::VENDOR => '5',
            ItemApiDtoInterface::ACTIVE => 'd',
            ItemApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ItemApiDtoInterface::POSITION => 7,
            NomenclatureApiDtoInterface::NOMENCLATURES => [4, 5],
            ItemApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ItemApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
            ItemApiDtoInterface::ATTRIBUTES => ['test_class' => 'test_log'],
        ],
    ];

    protected static string $class = BaseItem::class;

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function create(ObjectManager $manager): self
    {
        $short = static::getReferenceName();
        $shortNomenclature = NomenclatureFixtures::getReferenceName();
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = $this->getEntity();
            $entity
                ->setStandard($record[ItemApiDtoInterface::STANDARD])
                ->setVendor($record[ItemApiDtoInterface::VENDOR])
                ->setActive($record[ItemApiDtoInterface::ACTIVE])
                ->setPosition($record[ItemApiDtoInterface::POSITION])
                ->setAttributes($record[ItemApiDtoInterface::ATTRIBUTES])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

            if (\array_key_exists(ItemApiDtoInterface::IMAGE, $record)) {
                $entity
                    ->setImage($record[ItemApiDtoInterface::IMAGE]);
            }

            if (\array_key_exists(ItemApiDtoInterface::PREVIEW, $record)) {
                $entity
                    ->setPreview($record[ItemApiDtoInterface::PREVIEW]);
            }

            if (\array_key_exists(ItemApiDtoInterface::ATTACHMENT, $record)) {
                $entity
                    ->setAttachment($record[ItemApiDtoInterface::ATTACHMENT]);
            }

            if (\array_key_exists(ItemApiDtoInterface::NOMENCLATURES, $record)) {
                foreach ($record[ItemApiDtoInterface::NOMENCLATURES] as $value) {
                    $entity
                        ->addNomenclature($this->getReference($shortNomenclature.$value));
                }
            }

            $this->expandEntity($entity, $record);

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::ITEM_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 100;
    }
}
