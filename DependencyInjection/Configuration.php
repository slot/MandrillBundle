<?php

namespace Hip\MandrillBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hip_mandrill');


        $rootNode
        ->children()
            ->arrayNode('default')
                ->children()
                    ->scalarNode('sender')->end()
                    ->scalarNode('sender_name')->end()
                ->end()
            ->end()
            ->scalarNode('api_key')->defaultNull()->end()
        ->end();

        return $treeBuilder;
    }
}
