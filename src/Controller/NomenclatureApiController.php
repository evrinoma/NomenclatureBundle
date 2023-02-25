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
use Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDtoInterface;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureCannotBeSavedException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureInvalidException;
use Evrinoma\NomenclatureBundle\Exception\Nomenclature\NomenclatureNotFoundException;
use Evrinoma\NomenclatureBundle\Facade\Nomenclature\FacadeInterface;
use Evrinoma\NomenclatureBundle\Serializer\GroupInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class NomenclatureApiController extends AbstractWrappedApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/nomenclature/create", options={"expose": true}, name="api_nomenclature_create")
     * @OA\Post(
     *     tags={"nomenclature"},
     *     description="the method perform create nomenclature",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         example={
     *                             "class": "Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto",
     *                             "title": "bla bla",
     *                             "description": "bla bla",
     *                             "position": "0",
     *                             "items": {
     *                                 {
     *                                     "class": "Evrinoma\NomenclatureBundle\Dto\ItemApiDto",
     *                                     "id": "1",
     *                                 },
     *                             },
     *                         },
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="position", type="string"),
     *                         @OA\Property(property="items", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto"),
     *                                 @OA\Property(property="id", type="string", default="1"),
     *                             )
     *                         ),
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create nomenclature")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var NomenclatureApiDtoInterface $nomenclatureApiDto */
        $nomenclatureApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_NOMENCLATURE;

        try {
            $this->facade->post($nomenclatureApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create nomenclature', $json, $error);
    }

    /**
     * @Rest\Put("/api/nomenclature/save", options={"expose": true}, name="api_nomenclature_save")
     * @OA\Put(
     *     tags={"nomenclature"},
     *     description="the method perform save nomenclature for current entity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         example={
     *                             "class": "Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto",
     *                             "title": "bla bla",
     *                             "id": "1",
     *                             "description": "bla bla",
     *                             "active": "b",
     *                             "position": "0",
     *                             "items": {
     *                                 {
     *                                     "class": "Evrinoma\NomenclatureBundle\Dto\ItemApiDto",
     *                                     "id": "1",
     *                                 },
     *                             },
     *                         },
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto"),
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="active", type="string"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="position", type="string"),
     *                         @OA\Property(property="items", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="class", type="string", default="Evrinoma\NomenclatureBundle\Dto\ItemApiDto"),
     *                                 @OA\Property(property="id", type="string", default="1"),
     *                             )
     *                         ),
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save nomenclature")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var NomenclatureApiDtoInterface $nomenclatureApiDto */
        $nomenclatureApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_NOMENCLATURE;

        try {
            $this->facade->put($nomenclatureApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save nomenclature', $json, $error);
    }

    /**
     * @Rest\Delete("/api/nomenclature/delete", options={"expose": true}, name="api_nomenclature_delete")
     * @OA\Delete(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto",
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
     * @OA\Response(response=200, description="Delete nomenclature")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var NomenclatureApiDtoInterface $nomenclatureApiDto */
        $nomenclatureApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($nomenclatureApiDto, '', $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete nomenclature', $json, $error);
    }

    /**
     * @Rest\Get("/api/nomenclature/criteria", options={"expose": true}, name="api_nomenclature_criteria")
     * @OA\Get(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Entity description",
     *         in="query",
     *         name="description",
     *         @OA\Schema(
     *             type="string",
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
     *         description="position",
     *         in="query",
     *         name="position",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="title",
     *         in="query",
     *         name="title",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="item[id]",
     *         in="query",
     *         description="Type Nomenclature",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 ref=@Model(type=Evrinoma\NomenclatureBundle\Form\Rest\Item\ItemChoiceType::class, options={"data": "id"})
     *             ),
     *         ),
     *         style="form"
     *     ),
     * )
     * @OA\Response(response=200, description="Return nomenclature")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var NomenclatureApiDtoInterface $nomenclatureApiDto */
        $nomenclatureApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_NOMENCLATURE;

        try {
            $this->facade->criteria($nomenclatureApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get nomenclature', $json, $error);
    }

    /**
     * @Rest\Get("/api/nomenclature", options={"expose": true}, name="api_nomenclature")
     * @OA\Get(
     *     tags={"nomenclature"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto",
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
     * @OA\Response(response=200, description="Return nomenclature")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var NomenclatureApiDtoInterface $nomenclatureApiDto */
        $nomenclatureApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_NOMENCLATURE;

        try {
            $this->facade->get($nomenclatureApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get nomenclature', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof NomenclatureCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof NomenclatureNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof NomenclatureInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
