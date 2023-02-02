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
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Entity\Nomenclature\BaseNomenclature;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class NomenclatureFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            NomenclatureApiDtoInterface::TITLE => 'ite',
            NomenclatureApiDtoInterface::POSITION => 1,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'a',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'kzkt',
            NomenclatureApiDtoInterface::POSITION => 2,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'a',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'c2m',
            NomenclatureApiDtoInterface::POSITION => 3,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'a',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'kzkt2',
            NomenclatureApiDtoInterface::POSITION => 1,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'd',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'nvr',
            NomenclatureApiDtoInterface::POSITION => 2,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'b',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'nvr2',
            NomenclatureApiDtoInterface::POSITION => 3,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'd',
        ],
        [
            NomenclatureApiDtoInterface::TITLE => 'nvr3',
            NomenclatureApiDtoInterface::POSITION => 1,
            NomenclatureApiDtoInterface::DESCRIPTION => 'desc',
            NomenclatureApiDtoInterface::ACTIVE => 'd',
        ],
    ];

    protected static string $class = BaseNomenclature::class;

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
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = $this->getEntity();
            $entity
                ->setActive($record[NomenclatureApiDtoInterface::ACTIVE])
                ->setTitle($record[NomenclatureApiDtoInterface::TITLE])
                ->setPosition($record[NomenclatureApiDtoInterface::POSITION])
                ->setDescription($record[NomenclatureApiDtoInterface::DESCRIPTION])
                ->setCreatedAt(new \DateTimeImmutable())
            ;

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
            FixtureInterface::NOMENCLATURE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 0;
    }
}
