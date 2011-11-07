<?php
namespace Doubles\Mock;

interface IExpectation {

	/**
	 * Provide a closure that will be called when a method that is not
	 * expected by this test double instance is called. The callback
	 * will receive the method name and an array of the arguments passed
	 * to the method orignally.
	 */
	public function setUnexpectedMethodCallback(\Closure $callback);

}
