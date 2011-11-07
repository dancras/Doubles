<?php

use Doubles\Mock;

class MockTest extends PHPUnit_Framework_TestCase {

	public function testMockFromClassIsAnInstanceOfSubjectClass() {

		$mock = Mock::fromClass('SomeClass');
		$this->assertInstanceOf('SomeClass', $mock);
	}
	
	public function testMockFromInterfaceIsAnInstanceOfSubjectInterface() {

		$mock = Mock::fromInterface('SomeInterface');
		$this->assertInstanceOf('SomeInterface', $mock);
	}
	
	public function testUnexpectedMethodCallbackIsCalledWhenMethodIsNotMockedOrStubbed() {

		$mock = Mock::fromClass('SomeClass');

		$mock->setUnexpectedMethodCallback(function ($methodName, $arguments) use (&$m, &$a) {
			$m = $methodName;
			$a = $arguments;
		});
		
		$mock->someMethod('someValue');
		
		$this->assertSame('someMethod', $m);
		$this->assertEquals(array('someValue'), $a);
	}
	
	public function testStubReturnsValue() {

		$mock = Mock::fromClass('SomeClass');
		
		$dummyObject = new stdClass;
		$mock->stub('method', $dummyObject);
		
		$this->assertSame($dummyObject, $mock->method());

	}
	
	public function testStubThrowsExceptionWithoutAffectingSpy() {

		$mock = Mock::fromClass('SomeClass');
		
		$exception = new Exception;
		$mock->stub('method', $exception);
		
		try {
			$mock->method('someValue');
		} catch (Exception $e) {
			$actualException = $e;
		}
		
		$this->assertSame($exception, $actualException);
		$this->assertSame('someValue', $mock->spy('method')->arg(0,0));

	}
	
	public function testMockPerformsCallback() {
		
		$mock = Mock::fromClass('SomeClass');
		
		$mock->mock('method', function ($methodName, $arguments) use (&$m, &$a) {
			$m = $methodName;
			$a = $arguments;
			return 'someValue';
		});
		
		$this->assertSame('someValue', $mock->method('someArg'));
		$this->assertSame('method', $m);
		$this->assertEquals(array('someArg'), $a);
	}
	
}