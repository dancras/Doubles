<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Doubles;
use Doubles\Partial\PartialComponent;

class PartialComponentTest extends PHPUnit_Framework_TestCase
{
    public function testReturnsPartialInterfaceWhenMethodNotExpected()
    {
        $expectationComponent = Doubles::fromClass(
            '\Doubles\Expectation\ExpectationComponent'
        );
        $expectationComponent->stub('isMethodExpected', false);

        $component = new PartialComponent($expectationComponent);

        $this->assertInstanceOf(
            '\Doubles\Core\PartialInterface',
            $component->whenMethodCalled('foo', array())
        );
    }

    public function testDoesNothingWhenMethodExpected()
    {
        $expectationComponent = Doubles::fromClass(
            '\Doubles\Expectation\ExpectationComponent'
        );
        $expectationComponent->stub('isMethodExpected', true);

        $component = new PartialComponent($expectationComponent);

        $this->assertNull($component->whenMethodCalled('foo', array()));
    }
}

