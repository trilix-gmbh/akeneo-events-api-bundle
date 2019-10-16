<?php

namespace Trilix\EventsApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('trilix_events_api');

        $rootNode
            ->children()
                ->arrayNode('transport')
                    ->children()
                        ->scalarNode('factory')
                            ->isRequired()
                            ->defaultValue('pim_events_api.transport_factory.http')
                        ->end()
                        ->arrayNode('options')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
