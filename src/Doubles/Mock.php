<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles;

/**
 * 
 */
class Mock {
	
	/**
	 * @param string $className
	 * @return \Doubles\Mock\IMock
	 */
	public static function fromClass($className) {
		return self::create($className, T_CLASS);
	}

	/**
	 * @param string $interfaceName
	 * @return \Doubles\Mock\IMock
	 */
	public static function fromInterface($interfaceName) {
		return self::create($interfaceName, T_INTERFACE);
	}
	
	private static function create($subjectName, $type) {
		
		$subject = new Core\SimpleSubject($subjectName, $type);
		$testDouble = new Core\TestDouble;
		
		$expectationComponent = new Mock\ExpectationComponent;
		$stubComponent = new Mock\StubComponent;
		$mockComponent = new Mock\MockComponent;
		
		$expectationComponent->addExpecter($stubComponent);
		$expectationComponent->addExpecter($mockComponent);
		
		$testDouble->addComponent($expectationComponent);
		$testDouble->addComponent($stubComponent);
		$testDouble->addComponent($mockComponent);
		$testDouble->addComponent(new Spy\SpyComponent);

		return Core\TestDoubleFactory::create($subject, $testDouble, 'Mock%s');
	}
}
