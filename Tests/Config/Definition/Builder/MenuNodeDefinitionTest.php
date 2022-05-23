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
namespace Jb\Bundle\ConfigKnpMenuBundle\Tests\Unit\Config\Definition\Builder;

use Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder\MenuNodeDefinition;
use Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder\MenuTreeBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Jb\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder\MenuNodeDefinition
 */
class MenuNodeDefinitionTest extends TestCase
{
    /**
     * @var MenuTreeBuilder|MockObject
     */
    protected $builder;

    protected MenuNodeDefinition $definition;

    /**
     * Init Mock
     */
    protected function setUp(): void
    {
        $this->builder = $this
            ->getMockBuilder(MenuTreeBuilder::class)
            ->onlyMethods(
                array(
                    'node',
                    'children',
                    'scalarNode',
                    'end',
                    'menuNode',
                    'menuNodeHierarchy',
                    'defaultTrue',
                    'prototype'
                )
            )
            ->getMock();
        $this->definition = new MenuNodeDefinition('test');
        $this->definition->setBuilder($this->builder);
    }

    /**
     * Test that if depth is 0, then the menu node definition is returned
     */
    public function testMenuNodeHierarchyZeroDepth(): void
    {
        $this->builder->expects($this->never())
            ->method('node');

        $this->assertInstanceOf(
            MenuNodeDefinition::class,
            $this->definition->menuNodeHierarchy(0)
        );
    }

    /**
     * Test the recursive calls
     */
    public function testMenuNodeHierarchyNonZeroDepth(): void
    {
        $this->builder
            ->method('node')
            ->will($this->returnSelf());

        $this->builder
            ->method('children')
            ->will($this->returnSelf());

        $this->builder
            ->method('scalarNode')
            ->will($this->returnSelf());

        $this->builder
            ->method('end')
            ->will($this->returnSelf());

        $this->builder
            ->method('prototype')
            ->will($this->returnSelf());

        $this->builder->expects($this->once())
            ->method('menuNode')
            ->with('children')
            ->will($this->returnSelf());

        $this->builder->expects($this->once())
            ->method('menuNodeHierarchy')
            ->with(9)
            ->will($this->returnSelf());

        $this->builder
            ->method('defaultTrue')
            ->will($this->returnSelf());

        $this->definition->menuNodeHierarchy();
    }
}
