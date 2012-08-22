<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * This is used to communicate to the TestDouble class that we want to throw an
 * Exception, without actually throwing one. The TestDouble can call throwContents
 * when other functionality is complete.
 */
class ExceptionContainer {
	
	private $exception;
	
	public function throwContents() {
		throw $this->exception;
	}
	
	public function __construct(\Exception $exception) {
		$this->exception = $exception;
	}

}