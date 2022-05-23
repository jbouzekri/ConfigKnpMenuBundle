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
    public function setMenuRootName(string $rootName): void
    {
        $this->rootName = $rootName;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->rootName, 'array', new MenuTreeBuilder());

        $rootNode = $treeBuilder->getRootNode();

        // Tree node level added in order to keep the array keys for the first level of nodes
        $rootNode
            ->children()
                ->arrayNode('childrenAttributes')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->menuNode('tree')
                    ->menuNodeHierarchy()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
