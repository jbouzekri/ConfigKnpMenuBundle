<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection\JbConfigKnpMenuExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Jb\Bundle\ConfigKnpMenuBundle\Tests\DependencyInjection\Fixtures\Bundle1\JbTest1Bundle;
use Jb\Bundle\ConfigKnpMenuBundle\Tests\DependencyInjection\Fixtures\Bundle2\JbTest2Bundle;

/**
 * Test Extension
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class JbConfigKnpMenuExtensionTest extends TestCase
{
    /**
     * Test loading data from file
     */
    public function testLoading(): void
    {
        $menuConfiguration = self::loadConfiguration();

        $this->assertEquals('Second Item Label', $menuConfiguration['main']['tree']['second_item']['label']);
        $this->assertEquals('First Item Label', $menuConfiguration['main']['tree']['first_item']['label']);
        $this->assertCount(1, $menuConfiguration['main']['tree']['second_item']['children']);
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
     * @return ContainerBuilder
     */
    protected static function createContainer(array $data = array()): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag(array_merge(array(
            'kernel.bundles'     => array(
                'JbTest1Bundle' =>
                    JbTest1Bundle::class,
                'JbTest2Bundle' =>
                    JbTest2Bundle::class
            ),
            'kernel.root_dir' => 'app'
        ), $data)));
    }
}
