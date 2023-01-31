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

namespace Evrinoma\NomenclatureBundle;

use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Complex\NomenclaturePass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Property\ItemPass as PropertyItemPass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\Constraint\Property\NomenclaturePass as PropertyNomenclaturePass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\DecoratorPass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\MapEntityPass;
use Evrinoma\NomenclatureBundle\DependencyInjection\Compiler\ServicePass;
use Evrinoma\NomenclatureBundle\DependencyInjection\EvrinomaNomenclatureExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaNomenclatureBundle extends Bundle
{
    public const BUNDLE = 'nomenclature';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass(new PropertyNomenclaturePass())
            ->addCompilerPass(new PropertyItemPass())
            ->addCompilerPass(new NomenclaturePass())
            ->addCompilerPass(new DecoratorPass())
            ->addCompilerPass(new ServicePass())
        ;
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EvrinomaNomenclatureExtension();
        }

        return $this->extension;
    }
}
