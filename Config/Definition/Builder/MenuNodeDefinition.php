<?php

/**
 * Copyright 2014 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2014 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/ConfigKnpMenuBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/ConfigKnpMenuBundle
 */

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Configuration definition for menu nodes
 */
class MenuNodeDefinition extends ArrayNodeDefinition
{
    /**
     * Make menu hierarchy
     *
     * @param  int $depth
     *
     * @return MenuNodeDefinition
     */
    public function menuNodeHierarchy($depth = 10)
    {
        if ($depth == 0) {
            return $this;
        }

        return $this
            ->prototype('array')
                ->children()
                    ->scalarNode('route')->end()
                    ->arrayNode('routeParameters')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->scalarNode('uri')->end()
                    ->scalarNode('label')->end()
                    ->booleanNode('display')->defaultTrue()->end()
                    ->booleanNode('displayChildren')->defaultTrue()->end()
                    ->integerNode('order')->end()
                    ->arrayNode('attributes')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->arrayNode('linkAttributes')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->arrayNode('childrenAttributes')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->arrayNode('labelAttributes')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->arrayNode('roles')
                        ->prototype('scalar')
                        ->end()
                    ->end()
                    ->arrayNode('extras')
                        ->prototype('scalar')
                        ->end()
                    ->end()
                    ->menuNode('children')->menuNodeHierarchy($depth - 1)
                ->end()
            ->end();
    }
}
