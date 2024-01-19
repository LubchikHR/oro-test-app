<?php

namespace App\ChainCommandBundle;

use App\ChainCommandBundle\DependencyInjection\Compiler\ConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChainCommandBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ConsoleCommandPass());
    }
}
