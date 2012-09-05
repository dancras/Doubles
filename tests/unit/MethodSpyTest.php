<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Spy\MethodSpy;

class MethodSpyTest extends PHPUnit_Framework_TestCase
{
    public function testArgsWithNoArgumentReturnsAllArgumentsForAllCalls()
    {
        $methodSpy = new MethodSpy('foo');
        $methodSpy->log(array('abc'), 1);
        $methodSpy->log(array(123), 2);

        $this->assertEquals(
            array(
                array('abc'),
                array(123)
            ),
            $methodSpy->args()
        );
    }

    public function testFindArgsThrowsFailureExceptionWhenNoCallIsLoggedWithGivenArgs()
    {
        $this->setExpectedException('\Doubles\Core\FailureException');

        $methodSpy = new MethodSpy('foo');
        $methodSpy->findArgs('abc');
    }

    public function testFindArgsReturnsCallIndexWhenACallWasLoggedWithGivenArgs()
    {
        $methodSpy = new MethodSpy('foo');
        $methodSpy->log(array('abc'), 1);
        $methodSpy->log(array('abc', 123), 2);

        $this->assertSame(0, $methodSpy->findArgs('abc'));
        $this->assertSame(1, $methodSpy->findArgs('abc', 123));
    }

    public function testCheckArgsThrowsFailureExceptionWhenNoCallIsLoggedWithGivenArgs()
    {
        $this->setExpectedException('\Doubles\Core\FailureException');

        $methodSpy = new MethodSpy('foo');
        $methodSpy->checkArgs('abc');
    }

    public function testCheckArgsReturnsFluentInterfaceWhenACallWasLoggedWithGivenArgs()
    {
        $methodSpy = new MethodSpy('foo');
        $methodSpy->log(array('abc'), 1);

        $this->assertSame($methodSpy, $methodSpy->checkArgs('abc'));
    }
}