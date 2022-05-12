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

namespace Jb\Bundle\ConfigKnpMenuBundle\DependencyInjection;

use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JbConfigKnpMenuExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuredMenus = array();

        $systemPaths = array(
            'kernel.root_dir',
            'kernel.project_dir',
        );
        foreach ($systemPaths as $systemPath) {
            if (!$container->hasParameter($systemPath)) {
                continue;
            }
            $configuredMenus = $this->loadNavigationYaml(
                $container,
                $configuredMenus,
                $container->getParameter($systemPath) . '/config/navigation.yml'
            );
        }

        $bundles = $container->getParameter('kernel.bundles');
        foreach ($bundles as $bundle) {
            try {
                $reflection = new ReflectionClass($bundle);
                $configuredMenus = $this->loadNavigationYaml(
                    $container,
                    $configuredMenus,
                    dirname($reflection->getFileName()) . '/Resources/config/navigation.yml'
                );
            } catch (ReflectionException $e) {
            }
        }

        try {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('services.yml');
        } catch (Exception $e) {
        }

        // validate menu configurations
        foreach ($configuredMenus as $rootName => $menuConfiguration) {
            $configuration = new NavigationConfiguration();
            $configuration->setMenuRootName($rootName);
            $menuConfiguration[$rootName] = $this->processConfiguration(
                $configuration,
                array($rootName => $menuConfiguration)
            );
        }

        // Set configuration to be used in a custom service
        $container->setParameter('jb_config.menu.configuration', $configuredMenus);

        // Last argument of this service is always the menu configuration
        $container
            ->getDefinition('jb_config.menu.provider')
            ->addArgument($configuredMenus);
    }

    private function loadNavigationYaml(ContainerBuilder $container, array $configuredMenus, string $file): array
    {
        if (is_file($file)) {
            $configuredMenus = array_replace_recursive($configuredMenus, $this->parseFile($file));
            $container->addResource(new FileResource($file));
        }
        return $configuredMenus;
    }

    /**
     * Parse a navigation.yml file
     *
     * @param string $file
     *
     * @return array
     */
    public function parseFile(string $file): array
    {
        $bundleConfig = Yaml::parse(file_get_contents(realpath($file)));

        if (!is_array($bundleConfig)) {
            return array();
        }

        return $bundleConfig;
    }
}
