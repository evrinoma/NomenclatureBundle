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

namespace Evrinoma\NomenclatureBundle\Repository\Nomenclature;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureProxyException;
use Evrinoma\NomenclatureBundle\Mediator\Nomenclature\QueryMediatorInterface;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;

trait NomenclatureRepositoryTrait
{
    private QueryMediatorInterface $mediator;

    /**
     * @param NomenclatureInterface $nomenclature
     *
     * @return bool
     *
     * @throws NomenclatureCannotBeSavedException
     * @throws ORMException
     */
    public function save(NomenclatureInterface $nomenclature): bool
    {
        try {
            $this->persistWrapped($nomenclature);
        } catch (ORMInvalidArgumentException $e) {
            throw new NomenclatureCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param NomenclatureInterface $nomenclature
     *
     * @return bool
     */
    public function remove(NomenclatureInterface $nomenclature): bool
    {
        return true;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return array
     *
     * @throws NomenclatureNotFoundException
     */
    public function findByCriteria(NomenclatureApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilderWrapped($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $galleries = $this->mediator->getResult($dto, $builder);

        if (0 === \count($galleries)) {
            throw new NomenclatureNotFoundException('Cannot find nomenclature by findByCriteria');
        }

        return $galleries;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     *
     * @throws NomenclatureNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): NomenclatureInterface
    {
        /** @var NomenclatureInterface $nomenclature */
        $nomenclature = $this->findWrapped($id);

        if (null === $nomenclature) {
            throw new NomenclatureNotFoundException("Cannot find nomenclature with id $id");
        }

        return $nomenclature;
    }

    /**
     * @param string $id
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureProxyException
     * @throws ORMException
     */
    public function proxy(string $id): NomenclatureInterface
    {
        $nomenclature = $this->referenceWrapped($id);

        if (!$this->containsWrapped($nomenclature)) {
            throw new NomenclatureProxyException("Proxy doesn't exist with $id");
        }

        return $nomenclature;
    }
}
