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

namespace Evrinoma\NomenclatureBundle\Manager\Nomenclature;

use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureProxyException;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\NomenclatureBundle\Repository\Nomenclature\NomenclatureQueryRepositoryInterface;

final class QueryManager implements QueryManagerInterface
{
    private NomenclatureQueryRepositoryInterface $repository;

    public function __construct(NomenclatureQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return array
     *
     * @throws NomenclatureNotFoundException
     */
    public function criteria(NomenclatureApiDtoInterface $dto): array
    {
        try {
            $nomenclature = $this->repository->findByCriteria($dto);
        } catch (NomenclatureNotFoundException $e) {
            throw $e;
        }

        return $nomenclature;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureProxyException
     */
    public function proxy(NomenclatureApiDtoInterface $dto): NomenclatureInterface
    {
        try {
            if ($dto->hasId()) {
                $nomenclature = $this->repository->proxy($dto->idToString());
            } else {
                throw new NomenclatureProxyException('Id value is not set while trying get proxy object');
            }
        } catch (NomenclatureProxyException $e) {
            throw $e;
        }

        return $nomenclature;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureNotFoundException
     */
    public function get(NomenclatureApiDtoInterface $dto): NomenclatureInterface
    {
        try {
            $nomenclature = $this->repository->find($dto->idToString());
        } catch (NomenclatureNotFoundException $e) {
            throw $e;
        }

        return $nomenclature;
    }
}
