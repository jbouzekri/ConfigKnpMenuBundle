ConfigKnpMenuBundle
===================

Introduction
------------

This bundle provides a way to configure your knp menus in your bundles yml configuration.

For more information on knp menu, read :
* The [Knp Menu Documentation](https://github.com/KnpLabs/KnpMenu/blob/master/README.markdown)
* The [Knp Menu Bundle Documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/README.md)

This bundle was inspired by the [OroNavigationBundle](https://github.com/orocrm/platform/tree/master/src/Oro/Bundle/NavigationBundle) in oro crm.

Installation
------------

You can use composer for installation.

Add the repository to the composer.json file of your project and run the update or install command.

``` json
{
    "require": {
        "jbouzekri/config-knp-menu-bundle": "0.9.0"
    }
}
```

Then enable it in your AppKernel.php with the KnpMenuBundle :
``` php
$bundles = array(
    ...
    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Jb\Bundle\ConfigKnpMenuBundle\JbConfigKnpMenuBundle(),
);
```

Documentation
-------------

In order to use this bundle, you must define your menu configuration in a navigation.yml file in your bundle.

Example :
``` yml
my_mega_menu:
    tree:
        first_level_item:
            label: My first label
            children:
                second_level_item:
                    label: My second level
```

Then you need to define a service.
``` yml
my_project.menu.admin:
    class: Knp\Menu\MenuItem
    factory_service: jb_config.menu.builder
    factory_method: createMenu
    arguments:
      - "@request"
      - "my_mega_menu"
    scope: request
    tags:
        - { name: knp_menu.menu, alias: my_menu }
```

The second argument must match the name of the menu in navigation.yml.
The tag alias will be used in your twig template.

``` twig
{{ knp_menu_render('my_menu') }}
```

Configuration
-------------

This is the available configuration definition for an item.

``` yml
my_mega_menu:
    tree:
        first_level_item:
            uri: "An uri. Use it if you do not define route parameter"
            route: "A sf2 route without @"
            routeParameters: "an array of parameters to pass to the route"
            label: "My first label"
            order: An integer to sort the item in his level.
            attributes: An array of attributes passed to the knp item
            linkAttributes: An array of attributes passed to the a tag
            childrenAttributes: An array of attributes passed to the chidlren block
            display: boolean to hide the item
            displayChildren: boolean to hide the children
            children: # An array of subitems
                second_level_item:
                    label: My second level
```

This configuration matches the methods available in the Knp Menu Item class

Issues
------

* tree sub configuration property :
In the navigation.yml file, you must defined a tree key below your menu name. It provides another level to keep the first level item key after configuration parsing.
If someone know how to remove it, let me know.

