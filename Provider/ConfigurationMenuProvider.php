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
namespace Jb\Bundle\ConfigKnpMenuBundle\Provider;

use Closure;
use Jb\Bundle\ConfigKnpMenuBundle\Event\ConfigureMenuEvent;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * ConfigurationMenuProvider
 * Provide menu from configuration
 *
 * @author jobou
 */
class ConfigurationMenuProvider implements MenuProviderInterface
{
    protected FactoryInterface $factory;
    protected EventDispatcherInterface $dispatcher;
    protected AuthorizationCheckerInterface $authorizationChecker;
    protected array $configuration;

    /**
     * Constructor
     *
     * @param FactoryInterface $factory the knp menu factory
     * @param EventDispatcherInterface $dispatcher the event dispatcher
     * @param AuthorizationCheckerInterface $authorizationChecker security is_granted checker
     * @param array $configuration An array of menu configuration
     */
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $dispatcher,
        AuthorizationCheckerInterface $authorizationChecker,
        array $configuration = array()
    ) {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
        $this->authorizationChecker = $authorizationChecker;
        $this->configuration = $configuration;
    }

    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Load configuration of menus
     */
    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * Return configuration of menus
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }


    /**
     * {@inheritDoc}
     */
    public function get(string $name, array $options = []) : ItemInterface
    {
        // Create menu root item
        $menu = $this->factory->createItem($name);
        if (!empty($this->configuration[$name]['childrenAttributes'])) {
            $menu->setChildrenAttributes($this->configuration[$name]['childrenAttributes']);
        }

        // Sort first level of items
        $this->sortItems($this->configuration[$name]['tree']);

        // Append item recursively to root
        foreach ($this->configuration[$name]['tree'] as $key => $childConfiguration) {
            // If no rights granted. Do not display item.
            if (!$this->isGranted($childConfiguration)) {
                continue;
            }
            $this->createItem($menu, $key, $childConfiguration);
        }

        $this->dispatcher->dispatch(new ConfigureMenuEvent($this->factory, $menu), ConfigureMenuEvent::CONFIGURE);

        return $menu;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $name, array $options = []): bool
    {
        return !empty($this->configuration[$name]);
    }

    /**
     * Add item to the menu
     * WARNING : recursive function. Is executed while there are children to the item
     *
     * @param ItemInterface $parentItem the parent item
     * @param string $name the name of the new item
     * @param array $configuration the configuration for the new item
     */
    protected function createItem(ItemInterface $parentItem, string $name, array $configuration): void
    {
        $item = $parentItem->addChild($name, $configuration);

        // Recursive loop for appending children menu items
        if (!empty($configuration['children'])) {
            $this->sortItems($configuration['children']);
            foreach ($configuration['children'] as $childName => $childConfiguration) {
                // If no rights granted. Do not display item.
                if (!$this->isGranted($childConfiguration)) {
                    continue;
                }
                $this->createItem($item, $childName, $childConfiguration);
            }
        }
    }

    /**
     * Sort items according to the order key value
     *
     * @param array $items an array of items
     */
    protected function sortItems(array &$items): void
    {
        $this->safeUaSortItems($items, function ($item1, $item2) {
            if (empty($item1['order']) || empty($item2['order']) || $item1['order'] === $item2['order']) {
                return 0;
            }

            return ($item1['order'] < $item2['order']) ? -1 : 1;
        });
    }

    /**
     * Safe sort items
     * (taken from http://php.net/manual/en/function.uasort.php#114535)
     */
    protected function safeUaSortItems(array &$array, Closure $cmp_function): void
    {
        if (count($array) < 2) {
            return;
        }

        $halfway = (int)(count($array) / 2);
        $array1 = array_slice($array, 0, $halfway, true);
        $array2 = array_slice($array, $halfway, null, true);

        $this->safeUaSortItems($array1, $cmp_function);
        $this->safeUaSortItems($array2, $cmp_function);

        if ($cmp_function(end($array1), reset($array2)) < 1) {
            $array = $array1 + $array2;
            return;
        }

        $array = array();
        reset($array1);
        while (current($array1) && current($array2)) {
            if ($cmp_function(current($array1), current($array2)) < 1) {
                $array[key($array1)] = current($array1);
                next($array1);
            } else {
                $array[key($array2)] = current($array2);
                next($array2);
            }
        }
        while (current($array1)) {
            $array[key($array1)] = current($array1);
            next($array1);
        }
        while (current($array2)) {
            $array[key($array2)] = current($array2);
            next($array2);
        }
    }

    /**
     * Check if security context grant rights on menu item
     */
    protected function isGranted(array $configuration): bool
    {
        // If no role configuration. Grant rights.
        if (!isset($configuration['roles'])) {
            return true;
        }

        // If no configuration. Grant rights.
        if (!is_array($configuration['roles'])) {
            return true;
        }

        // Check if one of the role is granted
        foreach ($configuration['roles'] as $role) {
            if ($this->authorizationChecker->isGranted($role)) {
                return true;
            }
        }

        // Else return false
        return false;
    }
}
