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
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \Maestro\Bundle\NavigationBundle\Menu\ConfigurationLoader $configuration
     */
    public function __construct(FactoryInterface $factory, $configuration)
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
    }

    /**
     * Load a menu configuration
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Create a menu from the configuration loaded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $type
     *
     * @return \Knp\Menu\MenuItem
     */
    public function createMenu(Request $request, $type)
    {
        $menu = $this->factory->createItem('root');
var_dump($this->configuration);
        $menu->addChild('Home', array('route' => 'admin_homepage'));
        // ... add more children

        return $menu;
    }
}