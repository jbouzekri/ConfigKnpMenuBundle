<?php

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Event;

use Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent
 */
class ConfigureMenuEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent
     */
    protected $event;

    /**
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @var \Knp\Menu\ItemInterface
     */
    protected $menu;

    /**
     * Init Mock
     */
    public function setUp(): void
    {
        $this->factory = $this->createMock('Knp\Menu\FactoryInterface');
        $this->menu = $this->createMock('Knp\Menu\ItemInterface');

        $this->event = new ConfigureMenuEvent($this->factory, $this->menu);
    }

    /**
     * test event getter
     */
    public function testGetter()
    {
        $this->assertEquals($this->factory, $this->event->getFactory());
        $this->assertEquals($this->menu, $this->event->getMenu());
    }
}
