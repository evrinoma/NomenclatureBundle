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
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Factory\Nomenclature\FactoryInterface;
use Evrinoma\NomenclatureBundle\Mediator\Nomenclature\CommandMediatorInterface;
use Evrinoma\NomenclatureBundle\Model\Nomenclature\NomenclatureInterface;
use Evrinoma\NomenclatureBundle\Repository\Nomenclature\NomenclatureRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private NomenclatureRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    public function __construct(ValidatorInterface $validator, NomenclatureRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureInvalidException
     * @throws NomenclatureCannotBeCreatedException
     * @throws NomenclatureCannotBeSavedException
     */
    public function post(NomenclatureApiDtoInterface $dto): NomenclatureInterface
    {
        $nomenclature = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $nomenclature);

        $errors = $this->validator->validate($nomenclature);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new NomenclatureInvalidException($errorsString);
        }

        $this->repository->save($nomenclature);

        return $nomenclature;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @return NomenclatureInterface
     *
     * @throws NomenclatureInvalidException
     * @throws NomenclatureNotFoundException
     * @throws NomenclatureCannotBeSavedException
     */
    public function put(NomenclatureApiDtoInterface $dto): NomenclatureInterface
    {
        try {
            $nomenclature = $this->repository->find($dto->idToString());
        } catch (NomenclatureNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $nomenclature);

        $errors = $this->validator->validate($nomenclature);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new NomenclatureInvalidException($errorsString);
        }

        $this->repository->save($nomenclature);

        return $nomenclature;
    }

    /**
     * @param NomenclatureApiDtoInterface $dto
     *
     * @throws NomenclatureCannotBeRemovedException
     * @throws NomenclatureNotFoundException
     */
    public function delete(NomenclatureApiDtoInterface $dto): void
    {
        try {
            $nomenclature = $this->repository->find($dto->idToString());
        } catch (NomenclatureNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $nomenclature);
        try {
            $this->repository->remove($nomenclature);
        } catch (NomenclatureCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
