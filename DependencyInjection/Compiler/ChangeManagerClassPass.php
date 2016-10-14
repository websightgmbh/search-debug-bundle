<?php

namespace Websight\SearchDebugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Websight\SearchDebugBundle\Lucene\DebuggingLuceneManager;

class ChangeManagerClassPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('ivory_lucene_search')->setClass(DebuggingLuceneManager::class);
    }
}
