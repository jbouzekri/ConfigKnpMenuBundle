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
namespace Jb\Bundle\ConfigKnpMenuBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * ConfigureMenuEvent
 *
 * Use in post menu builder event
 */
class ConfigureMenuEvent extends Event
{
    const CONFIGURE = 'jb_config.navigation.menu_configure';

    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var \Knp\Menu\ItemInterface
     */
    private $menu;

    /**
     * Constructor
     *
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \Knp\Menu\ItemInterface $menu
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu)
    {
        $this->factory = $factory;
        $this->menu = $menu;
    }

    /**
     * @return \Knp\Menu\FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
