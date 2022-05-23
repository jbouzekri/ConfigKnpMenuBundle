<?php

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Event;

use Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent
 */
class ConfigureMenuEventTest extends TestCase
{
    /**
     * @var ConfigureMenuEvent
     */
    protected $event;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ItemInterface
     */
    protected $menu;

    /**
     * Init Mock
     */
    public function setUp(): void
    {
        $this->factory = $this->createMock(FactoryInterface::class);
        $this->menu = $this->createMock(ItemInterface::class);

        $this->event = new ConfigureMenuEvent($this->factory, $this->menu);
    }

    /**
     * test event getter
     */
    public function testGetter(): void
    {
        $this->assertEquals($this->factory, $this->event->getFactory());
        $this->assertEquals($this->menu, $this->event->getMenu());
    }
}
