<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles;

use PHPUnit_Framework_TestCase;
use Doubles\Stub\StubComponent;

class StubComponentTest extends PHPUnit_Framework_TestCase
{
    public function testStubAcceptsMultipleMethodNames()
    {
        $double = new StubComponent;
        $double->stub('foo', 'bar', 'abc');

        $this->assertSame('abc', $double->whenMethodCalled('foo', array()));
        $this->assertSame('abc', $double->whenMethodCalled('bar', array()));
    }

    public function testStubAcceptsAnArrayOfMethodNames()
    {
        $double = new StubComponent;
        $double->stub(array('foo', 'bar'), 'rocks', 'abc');

        $this->assertSame('abc', $double->whenMethodCalled('foo', array()));
        $this->assertSame('abc', $double->whenMethodCalled('bar', array()));
        $this->assertSame('abc', $double->whenMethodCalled('rocks', array()));
    }
}