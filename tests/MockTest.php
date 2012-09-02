<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

use Doubles\Doubles;

class MockTest extends PHPUnit_Framework_TestCase
{
    public function testMockFromClassIsAnInstanceOfSubjectClass()
    {
        $mock = Doubles::fromClass('\SomeClass');
        $this->assertInstanceOf('SomeClass', $mock);

        $mock = Doubles::fromClass('\SomeNamespace\SomeClass');
        $this->assertInstanceOf('SomeNamespace\SomeClass', $mock);
    }

    public function testMockFromInterfaceIsAnInstanceOfSubjectInterface()
    {
        $mock = Doubles::fromInterface('\SomeInterface');
        $this->assertInstanceOf('SomeInterface', $mock);

        $mock = Doubles::fromInterface('\SomeNamespace\SomeInterface');
        $this->assertInstanceOf('SomeNamespace\SomeInterface', $mock);
    }

    public function testUnexpectedMethodCallbackIsCalledWhenMethodIsNotMockedOrStubbed()
    {
        $mock = Doubles::fromClass('\SomeClass');

        $mock->setUnexpectedMethodCallback(
            function ($methodName, $arguments) use (&$m, &$a) {
                $m = $methodName;
                $a = $arguments;
            }
        );

        $mock->someMethod('someValue');

        $this->assertSame('someMethod', $m);
        $this->assertEquals(array('someValue'), $a);
    }

    public function testStubReturnsValue()
    {
        $mock = Doubles::fromClass('\SomeClass');

        $dummyObject = new stdClass;
        $mock->stub('method', $dummyObject);

        $this->assertSame($dummyObject, $mock->method());

    }

    public function testStubForProtectedMethod()
    {
        $mock = Doubles::fromClass('\Doubles\Test\Dummy');

        $dummyObject = new stdClass;
        $mock->stub('getProtectedValue', $dummyObject);

        $method = new ReflectionMethod($mock, 'getProtectedValue');
        $method->setAccessible(true);

        $this->assertSame($dummyObject, $method->invoke($mock));
    }

    public function testStubThrowsExceptionWithoutAffectingSpy()
    {
        $mock = Doubles::fromClass('\SomeClass');

        $exception = new Exception;
        $mock->stub('method', $exception);

        try {
            $mock->method('someValue');
        } catch (Exception $e) {
            $actualException = $e;
        }

        $this->assertSame($exception, $actualException);
        $this->assertSame('someValue', $mock->spy('method')->arg(0, 0));
    }

    public function testMockPerformsCallback()
    {
        $mock = Doubles::fromClass('\SomeClass');

        $mock->mock(
            'method',
            function ($methodName, $arguments) use (&$m, &$a) {
                $m = $methodName;
                $a = $arguments;

                return 'someValue';
            }
        );

        $this->assertSame('someValue', $mock->method('someArg'));
        $this->assertSame('method', $m);
        $this->assertEquals(array('someArg'), $a);
    }

    public function testFluentInterface()
    {
        $double = Doubles::fromClass('\SomeClass');
        $double->stub('foo', 'bar')
               ->stub('hello', 'world');

        $this->assertSame('bar', $double->foo());
        $this->assertSame('world', $double->hello());
    }
}

