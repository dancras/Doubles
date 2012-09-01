<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

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

    public function testProxyOnProtectedMethod()
    {
        $object = new \Doubles\Test\Dummy;
        $expectationComponent = new \Doubles\Expectation\ExpectationComponent;

        $component = new PartialComponent($object, $expectationComponent);

        $this->assertSame(
            'bar',
            $component->whenMethodCalled('getProtectedValue', array('bar', 123))
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

