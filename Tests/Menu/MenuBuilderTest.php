<?php

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Menu;

use Jb\Bundle\PhumborBundle\Tests\DependencyInjection\JbConfigKnpMenuExtensionTest;
use Knp\Menu\Silex\RouterAwareFactory;
use Jb\Bundle\ConfigKnpMenuBundle\Menu\MenuBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Menu\MenuBuilder
 */
class MenuBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Jb\Bundle\ConfigKnpMenuBundle\Menu\MenuBuilder
     */
    protected $menuBuilder;

    /**
     * Init Mock
     */
    public function setUp()
    {
        $urlGenerator = $this->getMock('Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface');
        $urlGenerator->expects($this->any())
            ->method('generate')
            ->will($this->returnValue('/my-page'));

        $menuFactory = new RouterAwareFactory($urlGenerator);

        $eventDispatcher = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        $configuration = JbConfigKnpMenuExtensionTest::loadConfiguration();

        $this->menuBuilder = new MenuBuilder($menuFactory, $eventDispatcher);
        $this->menuBuilder->setConfiguration($configuration);
    }

    /**
     * test createMenu
     */
    public function testCreateMenu()
    {
        $menu = $this->menuBuilder->createMenu(new Request(), 'main');

        $this->assertEquals(
            count($menu->getChildren()),
            5,
            'Menu item number'
        );

        $this->assertEquals(
            $menu->getChild('first_item')->getUri(),
            '/first-item',
            'First item uri'
        );

        $this->assertEquals(
            $menu->getChild('third_item')->getUri(),
            '/my-page',
            'Third item uri'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->getLabel(),
            'Third Item Label',
            'Third item label'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->getAttributes(),
            array('test' => 'test2'),
            'Third item attributes'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->getLinkAttributes(),
            array('test' => 'test3'),
            'Third item link attributes'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->getChildrenAttributes(),
            array('test' => 'test4'),
            'Third item children attributes'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->isDisplayed(),
            false,
            'Third item display'
        );
        $this->assertEquals(
            $menu->getChild('third_item')->getDisplayChildren(),
            false,
            'Third item display children'
        );

        $position = 0;
        foreach ($menu->getChildren() as $key => $item) {
            if ($key == 'first_item') {
                $this->assertEquals($position, 0, 'First item postion');
            }
            $position++;
        }

        $this->assertEquals(
            count($menu->getChild('first_item')->getChildren()),
            0,
            'First item children count'
        );
        $this->assertEquals(
            count($menu->getChild('second_item')->getChildren()),
            1,
            'Second item children count'
        );
        $this->assertEquals(
            count($menu->getChild('third_item')->getChildren()),
            0,
            'Third item children count'
        );

        $this->assertEquals(
            $menu->getChild('second_item')->getChild('second_item_first_child')->getLabel(),
            'First Child',
            'Second item child label'
        );
    }

    /**
     * test createMenu with multiple menu
     */
    public function testMultipleMenus()
    {
        $menu = $this->menuBuilder->createMenu(new Request(), 'second_menu');

        $this->assertEquals(
            $menu->getChild('item1')->getLabel(),
            'Item 1 Label',
            'Second menu item 1 label'
        );
        $this->assertEquals(
            $menu->getChild('item2')->getLabel(),
            'Item 2 Label',
            'Second menu item 2 label'
        );
    }

    /**
     * @expectedException \Jb\Bundle\ConfigKnpMenuBundle\Menu\Exception\MenuConfigurationNotFoundException
     */
    public function testUnknownMenuException()
    {
        $menu = $this->menuBuilder->createMenu(new Request(), 'unknown');
    }
}
