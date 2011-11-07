<?php
namespace Doubles\Spy;

interface ISpy {
	
	/**
	 * @param string $methodName
	 * @return \Doubles\Spy\MethodSpy
	 */
	public function spy($methodName);
	
	/**
	 * Return the number of calls across all methods on this instance.
	 * @return int
	 */
	public function callCount();
	
	/**
	 * Use CallCounter::shareNew() to distribute a CallCounter instance
	 * to several spy instances. It will then be possible to compare the
	 * order of method calls between these instances.
	 *
	 * @return void
	 */
	public function setSharedCallCounter(CallCounter $counter);

}
