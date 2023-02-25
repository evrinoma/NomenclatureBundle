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

namespace Evrinoma\NomenclatureBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\NomenclatureBundle\Dto\ItemApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Item\ItemNotFoundException;
use Evrinoma\NomenclatureBundle\Facade\Item\FacadeInterface;
use Evrinoma\NomenclatureBundle\Serializer\GroupInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ItemApiController extends AbstractWrappedApiController implements ApiControllerInterface
{
    private string $dtoClass;

    private ?Request $request;

    private FactoryDtoInterface $factoryDto;

    private FacadeInterface $facade;

    public function __construct(
        SerializerInterface $serializer,
        RequestStack $requestStack,
        FactoryDtoInterface $factoryDto,
        FacadeInterface $facade,
        string $dtoClass
    ) {
        parent::__construct($serializer);
        $this->request = $requestStack->getCurrentRequest();
        $this->factoryDto = $factoryDto;
        $this->dtoClass = $dtoClass;
        $this->facade = $facade;
    }

    /**
     * @Rest\Post("/api/nomenclature/item/create", options={"expose": true}, name="api_nomenclature_item_create")
     * @OA\Post(
     *     tags={"nomenclature"},
     *     description="the method perform create nomenclature type",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto"),
     *                         @OA\Property(property="vendor", type="string"),
     *                         @OA\Property(property="standard", type="string"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="nomenclatures", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto"),
     *                                 @OA\Property(property="id", type="string", default="1"),
     *                             )
     *                         ),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="preview", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[preview]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create nomenclature item")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var ItemApiDtoInterface $itemApiDto */
        $itemApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_NOMENCLATURE_ITEM;

        try {
            $this->facade->post($itemApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create nomenclature item', $json, $error);
    }

    /**
     * @Rest\Post("/api/nomenclature/item/save", options={"expose": true}, name="api_nomenclature_item_save")
     * @OA\Post(
     *     tags={"nomenclature"},
     *     description="the method perform save nomenclature item for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="active", type="string"),
     *                         @OA\Property(property="vendor", type="string"),
     *                         @OA\Property(property="standard", type="string"),
     *                         @OA\Property(property="nomenclatures", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto"),
     *                                 @OA\Property(property="id", type="string", default="1"),
     *                             )
     *                         ),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="preview", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[preview]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\ArticleBundle\Dto\ArticleApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save nomenclature item")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var ItemApiDtoInterface $itemApiDto */
        $itemApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_NOMENCLATURE_ITEM;

        try {
            $this->facade->put($itemApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save nomenclature item', $json, $error);
    }

    /**
     * @Rest\Delete("/api/nomenclature/item/delete", options={"expose": true}, name="api_nomenclature_item_delete")
     * @OA\Delete(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Delete nomenclature item")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var ItemApiDtoInterface $itemApiDto */
        $itemApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($itemApiDto, '', $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete nomenclature item', $json, $error);
    }

    /**
     * @Rest\Get("/api/nomenclature/item/criteria", options={"expose": true}, name="api_nomenclature_item_criteria")
     * @OA\Get(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="active",
     *         in="query",
     *         name="active",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="standard",
     *         in="query",
     *         name="standard",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="vendor",
     *         in="query",
     *         name="vendor",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * )
     * @OA\Response(response=200, description="Return nomenclature item")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var ItemApiDtoInterface $itemApiDto */
        $itemApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_NOMENCLATURE_ITEM;

        try {
            $this->facade->criteria($itemApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get nomenclature item', $json, $error);
    }

    /**
     * @Rest\Get("/api/nomenclature/item", options={"expose": true}, name="api_nomenclature_item")
     * @OA\Get(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return nomenclature item")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var ItemApiDtoInterface $itemApiDto */
        $itemApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_NOMENCLATURE_ITEM;

        try {
            $this->facade->get($itemApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get nomenclature item', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof ItemCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof ItemNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof ItemInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
