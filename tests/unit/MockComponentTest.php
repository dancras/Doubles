<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Mock\MockComponent;

class MockComponentTest extends PHPUnit_Framework_TestCase
{
    public function testCallableArraySyntaxWorksWithMock()
    {
        $double = Doubles::fromClass('\Dummy');
        $double->stub('foo', 'abc');

        $component = new MockComponent;
        $component->mock('foo', array($double, 'foo'));

        $this->assertSame('abc', $component->whenMethodCalled('foo', array()));
    }
}

