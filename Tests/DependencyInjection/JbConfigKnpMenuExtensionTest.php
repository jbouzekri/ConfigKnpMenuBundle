<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection\JbConfigKnpMenuExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Test Extension
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class JbConfigKnpMenuExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test loading data from file
     */
    public function testLoading()
    {
        $menuConfiguration = self::loadConfiguration();

        $this->assertEquals($menuConfiguration['main']['tree']['second_item']['label'], 'Second Item Label');
        $this->assertEquals($menuConfiguration['main']['tree']['first_item']['label'], 'First Item Label');
        $this->assertEquals(count($menuConfiguration['main']['tree']['second_item']['children']), 1);
    }

    public static function loadConfiguration()
    {
        $containerBuilder = self::createContainer();
        $extension = new JbConfigKnpMenuExtension();
        $extension->load(array(), $containerBuilder);

        return $containerBuilder->getParameter('jb_config.menu.configuration');
    }

    /**
     * Create a container
     *
     * @param array $data
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected static function createContainer($data = array())
    {
        $container = new ContainerBuilder(new ParameterBag(array_merge(array(
            'kernel.bundles'     => array(
                'JbTest1Bundle' =>
                    'Jb\\Bundle\\ConfigKnpMenuBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle1\\JbTest1Bundle',
                'JbTest2Bundle' =>
                    'Jb\\Bundle\\ConfigKnpMenuBundle\\Tests\\DependencyInjection\\Fixtures\\Bundle2\\JbTest2Bundle'
            ),
        ), $data)));

        return $container;
    }
}
