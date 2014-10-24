<?php

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Provider;

use Jb\Bundle\PhumborBundle\Tests\DependencyInjection\JbConfigKnpMenuExtensionTest;
use Knp\Menu\MenuFactory;
use Jb\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider
 */
class ConfigurationMenuProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Jb\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider
     */
    protected $configurationProvider;

    /**
     * Init Mock
     */
    public function setUp()
    {
        $routingExtension = $this->getMockBuilder('Knp\\Menu\\Integration\\Symfony\\RoutingExtension')
            ->disableOriginalConstructor()
            ->getMock();
        $routingExtension->expects($this->any())
            ->method('buildOptions')
            ->will($this->returnValue(array('uri' => '/my-page')));

        $securityContext = $this->getMockBuilder('Symfony\\Component\\Security\\Core\\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));

        $menuFactory = new MenuFactory();
        $menuFactory->addExtension($routingExtension);

        $eventDispatcher = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        $configuration = JbConfigKnpMenuExtensionTest::loadConfiguration();

        $this->configurationProvider = new ConfigurationMenuProvider($menuFactory, $eventDispatcher, $securityContext);
        $this->configurationProvider->setConfiguration($configuration);
    }

    /**
     * test get
     */
    public function testGet()
    {
        $menu = $this->configurationProvider->get('main');

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
     * test get with multiple menu
     */
    public function testMultipleMenus()
    {
        $menu = $this->configurationProvider->get('second_menu');

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
     * test with roles
     */
    public function testWithRoles()
    {
        $configurationProviderBackup = clone $this->configurationProvider;

        $securityContext = $this->getMockBuilder('Symfony\\Component\\Security\\Core\\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(false));

        $this->configurationProvider->setSecurityContext($securityContext);
        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertEquals(
            $menu->getChild('item2'),
            false,
            'not menu because no rights'
        );

        $securityContext = $this->getMockBuilder('Symfony\\Component\\Security\\Core\\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));
        $securityContext
            ->method('getToken')
            ->will($this->returnValue(true));
        $this->configurationProvider->setSecurityContext($securityContext);
        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertInstanceOf(
            'Knp\Menu\ItemInterface',
            $menu->getChild('item2'),
            'authenticated and rights'
        );
    }
}
