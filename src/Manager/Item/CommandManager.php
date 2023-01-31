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

namespace Evrinoma\NomenclatureBundle\Manager\Item;

use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeCreatedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeRemovedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Factory\Item\FactoryInterface;
use Evrinoma\NomenclatureBundle\Mediator\Item\CommandMediatorInterface;
use Evrinoma\NomenclatureBundle\Model\Item\ItemInterface;
use Evrinoma\NomenclatureBundle\Repository\Item\ItemRepositoryInterface;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface
{
    private ItemRepositoryInterface $repository;
    private ValidatorInterface            $validator;
    private FactoryInterface           $factory;
    private CommandMediatorInterface      $mediator;

    public function __construct(ValidatorInterface $validator, ItemRepositoryInterface $repository, FactoryInterface $factory, CommandMediatorInterface $mediator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemInvalidException
     * @throws ItemCannotBeCreatedException
     * @throws ItemCannotBeSavedException
     */
    public function post(ItemApiDtoInterface $dto): ItemInterface
    {
        $item = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $item);

        $errors = $this->validator->validate($item);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ItemInvalidException($errorsString);
        }

        $this->repository->save($item);

        return $item;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @return ItemInterface
     *
     * @throws ItemInvalidException
     * @throws ItemNotFoundException
     * @throws ItemCannotBeSavedException
     */
    public function put(ItemApiDtoInterface $dto): ItemInterface
    {
        try {
            $item = $this->repository->find($dto->idToString());
        } catch (ItemNotFoundException $e) {
            throw $e;
        }

        $this->mediator->onUpdate($dto, $item);

        $errors = $this->validator->validate($item);

        if (\count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new ItemInvalidException($errorsString);
        }

        $this->repository->save($item);

        return $item;
    }

    /**
     * @param ItemApiDtoInterface $dto
     *
     * @throws ItemCannotBeRemovedException
     * @throws ItemNotFoundException
     */
    public function delete(ItemApiDtoInterface $dto): void
    {
        try {
            $item = $this->repository->find($dto->idToString());
        } catch (ItemNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $item);
        try {
            $this->repository->remove($item);
        } catch (ItemCannotBeRemovedException $e) {
            throw $e;
        }
    }
}
