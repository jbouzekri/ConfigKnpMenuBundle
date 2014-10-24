<?php

namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\DependencyInjection;

use Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection\NavigationConfiguration;
use Symfony\Component\Config\Definition\Processor;

/**
 * Test Navigation Configuration
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class NavigationConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection\NavigationConfiguration
     */
    protected $navigationConfiguration;

    /**
     * Init mock
     */
    public function setUp()
    {
        $this->navigationConfiguration = new NavigationConfiguration();
        $this->navigationConfiguration->setMenuRootName('my_menu');
    }

    /**
     * Test the default configuration
     */
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->navigationConfiguration, array());

        $this->assertEquals(
            self::getBundleDefaultConfig(),
            $config
        );
    }

    /**
     * Build a random menu item
     *
     * @param integer $number
     *
     * @return array
     */
    public static function buildRandomMenuItem($number = 1)
    {
        return array(
            'uri' => 'http://www.google.fr',
            'route' => 'test'.$number,
            'routeParameters' => array(
                'test' => 'test'.$number
            ),
            'label' => 'Label'.$number,
            'order' => 1,
            'attributes' => array(
                'test' => 'test'.$number
            ),
            'linkAttributes' => array(
                'test' => 'test'.$number
            ),
            'childrenAttributes' => array(
                'test' => 'test'.$number
            ),
            'labelAttributes' => array(
                'test' => 'test'.$number
            ),
            'display' => true,
            'displayChildren' => true,
            'children' => array(),
            'roles' => array()
        );
    }

    /**
     * Test full tree configuration
     */
    public function testTransformationConfiguration()
    {

        $data = array('childrenAttributes' => array(), 'tree' => array('item1' => self::buildRandomMenuItem()));
        $data['tree']['item1']['children']['item1.1'] = self::buildRandomMenuItem();

        $processor = new Processor();
        $config = $processor->processConfiguration($this->navigationConfiguration, array($data));

        $this->assertEquals($config, $data);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @dataProvider getInvalidTypeData
     */
    public function testInvalidData($data)
    {
        $processor = new Processor();
        $processor->processConfiguration($this->navigationConfiguration, array($data));
    }

    /**
     * Data invalid format
     *
     * @return array
     */
    public function getInvalidTypeData()
    {
        return array(
            array( array('tree' => array('item1' => array('uri' => array()))) ),
            array( array('tree' => array('item1' => array('route' => array()))) ),
            array( array('tree' => array('item1' => array('routeParameters' => ''))) ),
            array( array('tree' => array('item1' => array('label' => array()))) ),
            array( array('tree' => array('item1' => array('order' => ''))) ),
            array( array('tree' => array('item1' => array('attributes' => ''))) ),
            array( array('tree' => array('item1' => array('linkAttributes' => ''))) ),
            array( array('tree' => array('item1' => array('childrenAttributes' => ''))) ),
            array( array('tree' => array('item1' => array('display' => array()))) ),
            array( array('tree' => array('item1' => array('displayChildren' => array()))) ),
            array( array('tree' => array('item1' => array('children' => ''))) ),
            array( array('tree' => array('item1' => array('labelAttributes' => ''))) ),
        );
    }

    /**
     * Get bundle default config
     *
     * @return array
     */
    protected static function getBundleDefaultConfig()
    {
        return array('childrenAttributes' => array(), 'tree' => array());
    }
}
