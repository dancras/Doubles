<?php
namespace Doubles\Mock;

interface IStub extends \Doubles\Spy\ISpy, IExpectation {

	/**
	 * Subsequent calls to $methodName will return the provided value. If
	 * $returnValue is an Exception or child of, it will be thrown
	 *
	 * @param string $methodName
	 * @param mixed $returnValue
	 */
	public function stub($methodName, $returnValue);

	/**
	 * @param string $methodName
	 */
	public function unstub($methodName);
	
}
