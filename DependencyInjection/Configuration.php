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
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    return isset($v['transport']) && !isset($v[$v['transport']]);
                })
                ->thenInvalid(sprintf('Events API: Transport is not configured.'))
            ->end()
            ->children()
                ->scalarNode('transport')->isRequired()->defaultValue('http')->end()
                ->arrayNode('http')
                    ->children()
                        ->scalarNode('request_url')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
