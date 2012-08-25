<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

use Doubles\Mock;
use Doubles\Spy;

class PartialTest extends PHPUnit_Framework_TestCase {

	public function testMethodsOfPartialAreUnaffected() {
		
		$instance = new \Doubles\Test\Dummy;
		$partial = Mock::partial($instance);
		$partial->setValue('value');
		$this->assertSame('value', $partial->getValue());

	}
	
	public function testSpyOnPartial() {

		$instance = new \Doubles\Test\Dummy;
		$partial = Mock::partial($instance);
		$partial->setValue('value');
		$this->assertSame('value', $partial->spy('setValue')->arg(0, 0));

	}
	
	public function testStubOnPartial() {

		$instance = Spy::fromClass('\Doubles\Test\Dummy');
		$partial = Mock::partial($instance);
		$partial->stub('getFixedValue', 'stub');
		$this->assertSame('stub', $partial->getFixedValue());
		$this->assertSame(0, $instance->spy('getFixedValue')->callCount());

	}
	
	public function testMockOnPartial() {

		$instance = Spy::fromClass('\Doubles\Test\Dummy');
		$partial = Mock::partial($instance);
		
		$partial->mock('setValue', function ($methodName, $arguments) use (&$m, &$a) {
			$m = $methodName;
			$a = $arguments;
			return 'someValue';
		});
		
		$this->assertSame('someValue', $partial->setValue('someArg'));
		$this->assertSame('setValue', $m);
		$this->assertEquals(array('someArg'), $a);
		$this->assertSame(0, $instance->spy('setValue')->callCount());
		
	}
	
}