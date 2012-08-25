<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Partial;

/**
 * 
 */
class PartialComponent implements \Doubles\Core\IComponent {
	
	private $instance;
	
	/** @var \Doubles\Mock\ExpectationComponent */
	private $expectationComponent;
	
	public function whenMethodCalled($methodName, array $arguments) {

		if ($this->expectationComponent->isMethodExpected($methodName)) {
			return;
		}
		
		return call_user_func_array(array($this->instance, $methodName), $arguments);
	}
	
	public function __construct(
		\Doubles\Core\InstanceSubject $subject,
		\Doubles\Mock\ExpectationComponent $expectationComponent
	) {
		$this->instance = $subject->getInstance();
		$this->expectationComponent = $expectationComponent;
	}

}