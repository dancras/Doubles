<?php
namespace Doubles\Mock;

interface IMock extends IStub {	

	/**
	 * Calls to $methodName will call the provided callback, passing the
	 * method name and an array of arguments
	 *
	 * @param string $methodName
	 * @param Closure $callback
	 */
	public function mock($methodName, $callback);
	
	/**
	 * @param string $methodName
	 */
	public function unmock($methodName);

}
