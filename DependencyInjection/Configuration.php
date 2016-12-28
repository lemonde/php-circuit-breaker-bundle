<?php

namespace CircuitBreakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('circuit_breaker');

        $rootNode
            ->children()
                ->integerNode('threshold')->end()
                ->integerNode('timeout')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
