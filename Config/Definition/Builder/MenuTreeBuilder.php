<?php

/**
 * Copyright (c) 2013 Oro, Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @license https://github.com/orocrm/platform/blob/master/src/Oro/Bundle/NavigationBundle/LICENSE
 * @link https://github.com/orocrm/platform/blob/master/src/Oro/Bundle/NavigationBundle
 */

/**
 * @namespace
 */
namespace Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;

/**
 * MenuTreeBuilder
 *
 * Register the new MenuNodeDefinition
 */
class MenuTreeBuilder extends NodeBuilder
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->nodeMapping['menu'] = MenuNodeDefinition::class;
    }

    /**
     * Creates a child menu node
     *
     * @param  string $name The name of the node
     * @return NodeParentInterface The child node
     */
    public function menuNode(string $name): NodeParentInterface
    {
        return $this->node($name, 'menu');
    }
}
