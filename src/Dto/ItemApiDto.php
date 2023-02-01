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

namespace Evrinoma\NomenclatureBundle\Dto;

use Evrinoma\DtoBundle\Annotation\Dto;
use Evrinoma\DtoBundle\Annotation\Dtos;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\AttachmentTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PreviewTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\AttributesTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\NomenclatureApiDtoTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\NomenclaturesApiDtoTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\StandardTrait;
use Evrinoma\NomenclatureBundle\DtoCommon\ValueObject\Mutable\VendorTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class ItemApiDto extends AbstractDto implements ItemApiDtoInterface
{
    use ActiveTrait;
    use AttachmentTrait;
    use AttributesTrait;
    use IdTrait;
    use ImageTrait;
    use NomenclatureApiDtoTrait;
    use NomenclaturesApiDtoTrait;
    use PositionTrait;
    use PreviewTrait;
    use StandardTrait;
    use VendorTrait;

    /**
     * @Dto(class="Evrinoma\NomenclatureBundle\Dto\NomenclatureApiDto", generator="genRequestNomenclatureApiDto")
     */
    protected ?NomenclatureApiDtoInterface $nomenclatureApiDto = null;

    /**
     * @Dtos(class="Evrinoma\ContactBundle\Dto\NomenclatureApiDto", generator="genRequestNomenclaturesApiDto", add="addNomenclaturesApiDto")
     *
     * @var NomenclatureApiDto []
     */
    protected array $nomenclaturesApiDto = [];

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(ItemApiDtoInterface::ID);
            $active = $request->get(ItemApiDtoInterface::ACTIVE);
            $position = $request->get(ItemApiDtoInterface::POSITION);
            $vendor = $request->get(ItemApiDtoInterface::VENDOR);
            $standard = $request->get(ItemApiDtoInterface::STANDARD);
            $attributes = $request->get(ItemApiDtoInterface::ATTRIBUTES, []);

            $files = ($request->files->has($this->getClass())) ? $request->files->get($this->getClass()) : [];

            try {
                if (\array_key_exists(ItemApiDtoInterface::IMAGE, $files)) {
                    $image = $files[ItemApiDtoInterface::IMAGE];
                } else {
                    $image = $request->get(ItemApiDtoInterface::IMAGE);
                    if (null !== $image) {
                        $image = new File($image);
                    }
                }
            } catch (\Exception $e) {
                $image = null;
            }

            try {
                if (\array_key_exists(ItemApiDtoInterface::PREVIEW, $files)) {
                    $preview = $files[ItemApiDtoInterface::PREVIEW];
                } else {
                    $preview = $request->get(ItemApiDtoInterface::PREVIEW);
                    if (null !== $preview) {
                        $preview = new File($preview);
                    }
                }
            } catch (\Exception $e) {
                $preview = null;
            }

            try {
                if (\array_key_exists(ItemApiDtoInterface::ATTACHMENT, $files)) {
                    $attachment = $files[ItemApiDtoInterface::ATTACHMENT];
                } else {
                    $attachment = $request->get(ItemApiDtoInterface::ATTACHMENT);
                    if (null !== $attachment) {
                        $attachment = new File($attachment);
                    }
                }
            } catch (\Exception $e) {
                $attachment = null;
            }

            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($preview) {
                $this->setPreview($preview);
            }
            if ($image) {
                $this->setImage($image);
            }
            if ($attachment) {
                $this->setAttachment($attachment);
            }
            if ($position) {
                $this->setPosition($position);
            }
            if ($attributes) {
                $this->setAttributes($attributes);
            }
            if ($vendor) {
                $this->setVendor($vendor);
            }
            if ($standard) {
                $this->setStandard($standard);
            }
        }

        return $this;
    }
}
