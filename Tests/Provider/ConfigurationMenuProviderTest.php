<?php

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Provider;

use Jb\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider;
use Jb\Bundle\PhumborBundle\Tests\DependencyInjection\JbConfigKnpMenuExtensionTest;
use Knp\Menu\MenuFactory;
use Knp\Menu\Integration\Symfony\RoutingExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Knp\Menu\ItemInterface;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider
 */
class ConfigurationMenuProviderTest extends TestCase
{
    /**
     * @var ConfigurationMenuProvider
     */
    protected $configurationProvider;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * Init Mock
     */
    protected function setUp(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator
          ->method('generate')
          ->willReturn('/my-page');

        $this->authorizationChecker = $this->createMock(
            AuthorizationCheckerInterface::class
        );

        $menuFactory = new MenuFactory();
        $menuFactory->addExtension(new RoutingExtension($urlGenerator));

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $configuration = JbConfigKnpMenuExtensionTest::loadConfiguration();

        $this->configurationProvider = new ConfigurationMenuProvider(
            $menuFactory,
            $eventDispatcher,
            $this->authorizationChecker,
            $configuration
        );
    }

    /**
     * test get
     */
    public function testGet(): void
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('main');

        $this->assertCount(
            5,
            $menu->getChildren(),
            'Menu item number'
        );

        $this->assertEquals(
            '/first-item',
            $menu->getChild('first_item')->getUri(),
            'First item uri'
        );

        $this->assertEquals(
            '/my-page',
            $menu->getChild('third_item')->getUri(),
            'Third item uri'
        );

        $this->assertEquals(
            'Third Item Label',
            $menu->getChild('third_item')->getLabel(),
            'Third item label'
        );
        $this->assertEquals(
            array('test' => 'test2'),
            $menu->getChild('third_item')->getAttributes(),
            'Third item attributes'
        );
        $this->assertEquals(
            array('test' => 'test3'),
            $menu->getChild('third_item')->getLinkAttributes(),
            'Third item link attributes'
        );
        $this->assertEquals(
            array('test' => 'test4'),
            $menu->getChild('third_item')->getChildrenAttributes(),
            'Third item children attributes'
        );
        $this->assertFalse(
            $menu->getChild('third_item')->isDisplayed(),
            'Third item display'
        );
        $this->assertFalse(
            $menu->getChild('third_item')->getDisplayChildren(),
            'Third item display children'
        );

        $this->assertEquals(
            array(
              'key1' => 'value1',
              'key2' => 'value2',
              'routes' => array(
                array('pattern' => '/^foo/'),
                array('pattern' => '/^bar/'),
                array('route' => 'my_route', 'parameters' => array('test' => 'test1'))
              )
            ),
            $menu->getChild('third_item')->getExtras(),
            'Third item extras'
        );

        $position = 0;
        foreach ($menu->getChildren() as $key => $item) {
            if ($key === 'first_item') {
                $this->assertEquals(0, $position, 'First item position');
            }
            $position++;
        }

        $this->assertCount(
            0,
            $menu->getChild('first_item')->getChildren(),
            'First item children count'
        );
        $this->assertCount(
            1,
            $menu->getChild('second_item')->getChildren(),
            'Second item children count'
        );
        $this->assertCount(
            0,
            $menu->getChild('third_item')->getChildren(),
            'Third item children count'
        );

        $this->assertEquals(
            'First Child',
            $menu->getChild('second_item')->getChild('second_item_first_child')->getLabel(),
            'Second item child label'
        );
    }

    /**
     * test get with multiple menu
     */
    public function testMultipleMenus(): void
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('second_menu');

        $this->assertEquals(
            'Item 1 Label',
            $menu->getChild('item1')->getLabel(),
            'Second menu item 1 label'
        );
        $this->assertEquals(
            'Item 2 Label',
            $menu->getChild('item2')->getLabel(),
            'Second menu item 2 label'
        );
    }

    /**
     * test with roles
     */
    public function testWithRolesNotGranted(): void
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(false);

        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertNull(
            $menu->getChild('item2'),
            'not menu because no rights'
        );
    }

    /**
     * test with roles
     */
    public function testWithRolesGranted(): void
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertInstanceOf(
            ItemInterface::class,
            $menu->getChild('item2'),
            'authenticated and rights'
        );
    }
}
