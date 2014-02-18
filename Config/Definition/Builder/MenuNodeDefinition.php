<?php

namespace Maestro\Bundle\NavigationBundle\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Configuration definition for menu nodes
 */
class MenuNodeDefinition extends ArrayNodeDefinition
{
    /**
     * Make menu hierarchy
     *
     * @param  int                $depth
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
                    ->scalarNode('url')->end()
                    ->scalarNode('label')->end()
                    ->menuNode('children')->menuNodeHierarchy($depth - 1)
                ->end()
            ->end();
    }
}