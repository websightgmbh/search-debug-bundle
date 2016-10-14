<?php

namespace Websight\SearchDebugBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Websight\SearchDebugBundle\DependencyInjection\Compiler\ChangeManagerClassPass;

class WebsightSearchDebugBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ChangeManagerClassPass());
    }
}
