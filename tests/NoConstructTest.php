<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

use Doubles\Mock;

class NoConstructTest extends PHPUnit_Framework_TestCase {

	public function testConstructorIsSkippedForNoConstruct() {

		$instance = Mock::noConstruct('\Doubles\Test\Dummy');
		$this->assertInstanceOf('\Doubles\Test\Dummy', $instance);
		$this->assertSame('before', $instance->getConstructedValue());

	}
	
}