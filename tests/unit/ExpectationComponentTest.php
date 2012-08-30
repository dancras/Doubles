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

        $this->assertUnexpectedCallback($component, 'foo');
    }

    public function testIsIgnoredNotExpectedWhenMethodIgnored()
    {
        $component = new ExpectationComponent;
        $component->ignore('foo');

        $this->assertTrue($component->isMethodIgnored('foo'));
        $this->assertFalse($component->isMethodExpected('foo'));
    }

    public function testNoCallbackWhenMethodIgnored()
    {
        $component = new ExpectationComponent;

        $component->ignore('foo');

        $this->failOnUnexpectedCallback($component, 'foo');
    }

    public function testUnexpectedCallbackWhenMethodUnignored()
    {
        $component = new ExpectationComponent;

        $component->ignore('foo');
        $component->unignore('foo');

        $this->assertUnexpectedCallback($component, 'foo');
    }

    public function testIsExpectedNotIgnoredWhenMethodExpected()
    {
        $component = new ExpectationComponent;
        $expecter = $this->setUpExpecter($component);

        $this->assertTrue($component->isMethodExpected('foo'));
        $this->assertFalse($component->isMethodIgnored('foo'));

        $this->assertSame('foo', $expecter->spy('isExpecting')->oneCallArg(0));
    }

    public function testNoCallbackWhenMethodExpected()
    {
        $component = new ExpectationComponent;
        $expecter = $this->setUpExpecter($component);

        $this->failOnUnexpectedCallback($component, 'foo');

        $this->assertSame('foo', $expecter->spy('isExpecting')->oneCallArg(0));
    }

    private function setUpExpecter($component)
    {
        $expecter = Doubles::fromInterface('\Doubles\Expectation\IExpecter');
        $expecter->stub('isExpecting', true);

        $component->addExpecter($expecter);

        return $expecter;
    }

    private function assertUnexpectedCallback($component, $methodName)
    {
        $component->setUnexpectedMethodCallback(
            function ($methodName, $arguments) use (&$m, &$a) {
                $m = $methodName;
                $a = $arguments;
            }
        );

        $component->whenMethodCalled($methodName, array('bar'));

        $this->assertSame($methodName, $m);
        $this->assertEquals(array('bar'), $a);
    }

    private function failOnUnexpectedCallback($component, $methodName)
    {
        $that = $this;
        $component->setUnexpectedMethodCallback(
            function () use ($that) {
                $that->fail('No callback expected');
            }
        );

        $component->whenMethodCalled($methodName, array());
    }
}

