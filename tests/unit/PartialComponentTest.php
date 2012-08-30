<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Doubles;
use Doubles\Partial\PartialComponent;

class PartialComponentTest extends PHPUnit_Framework_TestCase
{
    public function testActsAsProxyWhenMethodNotExpected()
    {
        $object = Doubles::fromClass('\Doubles\Test\Dummy');
        $expectationComponent = Doubles::fromClass(
            '\Doubles\Expectation\ExpectationComponent'
        );
        $expectationComponent->stub('isMethodExpected', false);

        $component = new PartialComponent($object, $expectationComponent);
        $component->whenMethodCalled('foo', array('bar', 123));

        $this->assertEquals(
            array('bar', 123),
            $object->spy('foo')->oneCallArgs()
        );

        $this->assertSame(
            'foo',
            $expectationComponent->spy('isMethodExpected')->oneCallArg(0)
        );
    }

    public function testDoesNothingWhenMethodExpected()
    {
        $object = Doubles::fromClass('\Doubles\Test\Dummy');
        $expectationComponent = Doubles::fromClass(
            '\Doubles\Expectation\ExpectationComponent'
        );
        $expectationComponent->stub('isMethodExpected', true);

        $component = new PartialComponent($object, $expectationComponent);
        $component->whenMethodCalled('foo', array());

        $this->assertSame(0, $object->callCount());
    }
}

