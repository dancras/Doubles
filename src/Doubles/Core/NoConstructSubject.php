<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * Creating a test double from this class will create an instance with all
 * methods untouched, without calling the original constructor.
 */
class NoConstructSubject extends Subject {
	
	public function getMethodNames() {
		return array();
	}
}