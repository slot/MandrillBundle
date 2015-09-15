<?php

namespace Hip\MandrillBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * Copyright (c) 2013 Hipaway Travel GmbH, Berlin
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author: Sven Loth <sven.loth@hipaway.com>
 * @copyright: 2013 Hipaway Travel GmbH, Berlin
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
                ->isRequired()
                ->children()
                    ->scalarNode('sender')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('sender_name')->defaultNull()->end()
                    ->scalarNode('subaccount')->defaultNull()->end()
                ->end()
            ->end()
            ->scalarNode('api_key')->defaultNull()->end()
            ->scalarNode('disable_delivery')->defaultFalse()->end()
            ->scalarNode('debug')->defaultFalse()->end()
            ->arrayNode('proxy')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('use')->defaultFalse()->end()
                    ->scalarNode('host')->defaultNull()->end()
                    ->scalarNode('port')->defaultNull()->end()
                    ->scalarNode('user')->defaultNull()->end()
                    ->scalarNode('password')->defaultNull()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
