<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * 
 */
class SimpleSubject implements ISubject {
	
	private $name;
	private $type;
	
	public function getName() {
		return $this->name;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getMethodNames() {
		$reflectSubject = new \ReflectionClass($this->name);
		$methods = $reflectSubject->getMethods(\ReflectionMethod::IS_PUBLIC);
		
		$methodNames = array();

		foreach ($methods as $method) {
			$methodNames[] = $method->name;
		}
		
		return $methodNames;
	}
	
	public function __construct($name, $type) {
		
		if ($type !== T_CLASS && $type !== T_INTERFACE) {
			throw new \Exception;
		}
		
		$this->name = $name;
		$this->type = $type;
	}

}