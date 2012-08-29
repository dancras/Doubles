<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Doubles;
use Doubles\Expectation\ExpectationComponent;

class ExpectationComponentTest extends PHPUnit_Framework_TestCase
{
    public function testUnexpectedCallbackWhenMethodNeverExpected()
    {
        $component = new ExpectationComponent;

        $this->doUnexpectedCallbackTest($component, 'foo');
    }

    public function testNoCallbackWhenMethodExpected()
    {
        $component = new ExpectationComponent;

        $that = $this;
        $component->setUnexpectedMethodCallback(function () use ($that) {
            $that->fail('No callback expected');
        });

        $component->expect('foo');

        $this->assertTrue($component->isMethodExpected('foo'));

        $component->whenMethodCalled('foo', array());
    }

    public function testUnexpectedCallbackWhenMethodSetToUnexpected()
    {
        $component = new ExpectationComponent;

        $component->expect('foo');
        $component->unexpect('foo');

        $this->doUnexpectedCallbackTest($component, 'foo');
    }

    private function doUnexpectedCallbackTest($component, $methodName)
    {
        $this->assertFalse($component->isMethodExpected($methodName));

        $component->setUnexpectedMethodCallback(function ($methodName, $arguments) use (&$m, &$a) {
            $m = $methodName;
            $a = $arguments;
        });

        $component->whenMethodCalled($methodName, array('bar'));

        $this->assertSame($methodName, $m);
        $this->assertEquals(array('bar'), $a);
    }
}

