<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

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

