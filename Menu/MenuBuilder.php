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
    public function __construct(FactoryInterface $factory, $configuration)
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
     * @return \Knp\Menu\MenuItem
     */
    public function createMenu(Request $request, $type)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'admin_homepage'));
        // ... add more children

        return $menu;
    }
}