<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles;

/**
 * 
 */
class Spy {
	
	/**
	 * @param string $className
	 * @return \Doubles\Spy\ISpy
	 */
	public static function fromClass($className) {
		return self::create($className, T_CLASS);
	}

	/**
	 * @param string $interfaceName
	 * @return \Doubles\Spy\ISpy
	 */
	public static function fromInterface($interfaceName) {
		return self::create($interfaceName, T_INTERFACE);
	}
	
	private static function create($subjectName, $type) {
		$subject = new Core\SimpleSubject($subjectName, $type);
		$testDouble = new Core\TestDouble;
		$testDouble->addComponent(new Spy\SpyComponent);
		return Core\TestDoubleFactory::create($subject, $testDouble, 'Spy%s');
	}
}