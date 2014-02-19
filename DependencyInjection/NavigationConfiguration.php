<?php

namespace Maestro\Bundle\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Maestro\Bundle\NavigationBundle\Config\Definition\Builder\MenuTreeBuilder;

class NavigationConfiguration implements ConfigurationInterface
{
    protected $rootName = false;

    /**
     * Set the menu root name
     *
     * @param string $rootName the menu root name
     */
    public function setMenuRootName($rootName)
    {
        $this->rootName = $rootName;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root($this->rootName, 'array', new MenuTreeBuilder());

        // Tree node level added in order to keep the array keys for the first level of nodes
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->menuNode('tree')
                    ->menuNodeHierarchy()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}