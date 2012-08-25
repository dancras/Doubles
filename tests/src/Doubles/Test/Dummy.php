<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Test;

/**
 * Some features of the doubles framework operate on existing classes. This
 * dummy class is used to test those.
 */
class Dummy {
	
	private $constructedValue = 'before';
    
    protected function _getProtectedValue() {
        return null;
    }
	
	public function getConstructedValue() {
		return $this->constructedValue;
	}
	
	public function __construct() {
		$this->constructedValue = null;
	}
}