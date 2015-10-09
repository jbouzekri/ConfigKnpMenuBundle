ConfigKnpMenuBundle
===================

[![Build Status](https://travis-ci.org/jbouzekri/ConfigKnpMenuBundle.svg?branch=master)](https://travis-ci.org/jbouzekri/ConfigKnpMenuBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0ba3e2e6-4b91-4886-aa8d-4620eb243845/mini.png)](https://insight.sensiolabs.com/projects/0ba3e2e6-4b91-4886-aa8d-4620eb243845)

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
        "jbouzekri/config-knp-menu-bundle": "3.1.0"
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

In order to use this bundle, you must define your menu configuration in a **navigation.yml** file in your bundle.

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

It will configure a provider for knp menu factory. You can then use your my_mega_menu in twig as a classic knp menu :

``` twig
{{ knp_menu_render('my_mega_menu') }}
```

Configuration
-------------

This is the available configuration definition for an item.

``` yml
my_mega_menu:
    childrenAttributes: An array of attributes passed to the root ul tag
    tree:
        first_level_item:
            uri: "An uri. Use it if you do not define route parameter"
            route: "A sf2 route without @"
            routeParameters: "an array of parameters to pass to the route"
            label: "My first label OR "translatable.tag" from default domain (message.yml)"
            order: An integer to sort the item in his level.
            attributes: An array of attributes passed to the knp item
            linkAttributes: An array of attributes passed to the a tag
            childrenAttributes: An array of attributes passed to the chidlren block
            labelAttributes: An array of attributes passed to the label tag
            display: boolean to hide the item
            displayChildren: boolean to hide the children
            roles: array of item (string roles) passed to isGranted securityContext method to check if user has rights in menu items
            children: # An array of subitems
                second_level_item:
                    label: My second level
```

This configuration matches the methods available in the Knp Menu Item class

Menu security
-------------

Security context is injected in menu item provider.

For root menu item, display or hide it in your twig template.
For children items, if you didn't add the roles key, they will be displayed.
Else it will passed the array of key to the isGranted method and check if you have rights on the the item.

Issues
------

* tree sub configuration property :
In the navigation.yml file, you must defined a tree key below your menu name. It provides another level to keep the first level item key after configuration parsing.
If someone know how to remove it, let me know.
