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

        $rootNode = $treeBuilder->root($this->rootName, 'menu', new MenuTreeBuilder());

        $rootNode->menuNodeHierarchy();

        return $treeBuilder;
    }

    /**
     * Add children nodes to menu
     *
     * @param $node NodeBuilder
     * @return Configuration
     */
    protected function setChildren($node)
    {

    }
}