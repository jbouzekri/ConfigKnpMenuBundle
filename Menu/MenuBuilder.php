<?php

namespace Maestro\Bundle\NavigationBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    /**
     * the knp menu factory
     *
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * An array of menu configuration
     *
     * @var array
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param \Knp\Menu\FactoryInterface $factory the knp menu factory
     * @param array $configuration An array of menu configuration
     */
    public function __construct(FactoryInterface $factory, $configuration = array())
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
    }

    /**
     * Load configuration of menus
     *
     * @param array $configuration An array of menu configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Create a menu from the configuration loaded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request the symfony request
     * @param string $type the type of menu to load. It must match a key in the first level of configuration array
     *
     * @return \Knp\Menu\ItemInterface
     *
     * @throws \Maestro\Bundle\NavigationBundle\Menu\Exception\MenuConfigurationNotFoundException
     */
    public function createMenu(Request $request, $type)
    {
        if (empty($this->configuration[$type])) {
            throw new Exception\MenuConfigurationNotFoundException($type." configuration not found");
        }

        $menu = $this->factory->createItem('root');

        $this->sortItems($this->configuration[$type]);
        foreach ($this->configuration[$type] as $name => $childConfiguration) {
            $this->createItem($menu, $name, $childConfiguration);
        }

        return $menu;
    }

    /**
     * Add item to the menu
     * WARNING : recursive function. Is executed while there are children to the item
     *
     * @param \Knp\Menu\ItemInterface $parentItem the parent item
     * @param string $name the name of the new item
     * @param array $configuration the configuration for the new item
     */
    protected function createItem($parentItem, $name, $configuration)
    {
        $item = $parentItem->addChild($name);

        if (!empty($configuration['children'])) {
            $this->sortItems($configuration['children']);
            foreach ($configuration['children'] as $childName => $childConfiguration) {
                $this->createItem($item, $childName, $childConfiguration);
            }
        }
    }

    /**
     * Sort items according to the order key value
     *
     * @param array $items an array of items
     */
    protected function sortItems(&$items)
    {
        uasort($items, function ($item1, $item2) {
            if (empty($item1['order']) || empty($item2['order']) || $item1['order'] == $item2['order']) {
                return 0;
            }

            return ($item1['order'] < $item2['order']) ? -1 : 1;
        });
    }
}