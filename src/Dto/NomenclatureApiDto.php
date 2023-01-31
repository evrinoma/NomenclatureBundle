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

use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Mutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\DescriptionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\IdTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\ImageTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\PositionTrait;
use Evrinoma\DtoCommon\ValueObject\Mutable\TitleTrait;
use Symfony\Component\HttpFoundation\Request;

class NomenclatureApiDto extends AbstractDto implements NomenclatureApiDtoInterface
{
    use ActiveTrait;
    use DescriptionTrait;
    use DescriptionTrait;
    use IdTrait;
    use ImageTrait;
    use PositionTrait;
    use TitleTrait;

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id = $request->get(NomenclatureApiDtoInterface::ID);
            $active = $request->get(NomenclatureApiDtoInterface::ACTIVE);
            $title = $request->get(NomenclatureApiDtoInterface::TITLE);
            $position = $request->get(NomenclatureApiDtoInterface::POSITION);
            $description = $request->get(NomenclatureApiDtoInterface::DESCRIPTION);

            if ($active) {
                $this->setActive($active);
            }
            if ($id) {
                $this->setId($id);
            }
            if ($position) {
                $this->setPosition($position);
            }
            if ($title) {
                $this->setTitle($title);
            }
            if ($description) {
                $this->setDescription($description);
            }
        }

        return $this;
    }
}
