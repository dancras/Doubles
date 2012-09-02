<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Core\TestDouble;
use Doubles\Test\DummyComponent;

class TestDoubleTest extends PHPUnit_Framework_TestCase
{
    public function testWhenUndefinedMethodCalledReturnsGeneratedDoubleWhenComponentMethodReturnsNull()
    {
        $sut = new TestDouble;

        $component = new DummyComponent;
        $sut->addComponent($component);

        $this->assertInstanceOf(
            '\Doubles\Core\FluentInterface',
            $sut->whenUndefinedMethodCalled('dummy', array())
        );
    }
}

