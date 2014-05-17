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
namespace Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder\MenuTreeBuilder;

/**
 * Configuration for the navigation
 */
class NavigationConfiguration implements ConfigurationInterface
{
    /**
     * The menu name
     *
     * @var string
     */
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
            ->children()
                ->menuNode('tree')
                    ->menuNodeHierarchy()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
