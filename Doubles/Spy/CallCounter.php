<?php
namespace Doubles\Spy;

use \Doubles\Core\DoublesException;

/**
 * Simple incremental counting mechanism with a static function to distribute a
 * new instance to several objects simultaneously
 */
class CallCounter {
	
	/** @var int */
	private $count = 0;
	
	/**
	 * Creates a new instance and sets it on all params
	 * 
	 * @return void
	 * @throws \Doubles\DoublesException  When provided param is missing setSharedCallCounter() or
	 *                                    no params are provided
	 */
	public static function shareNew() {
		
		$counter = new self();
		
		$recipients = func_get_args();
		
		if (count($recipients) === 0) {
			throw new DoublesException('At least one parameter is expected');
		}
		
		foreach($recipients as $r) {			
			$r->setSharedCallCounter($counter);
		}

	}
	
	/**
	 * Increment the count for this instance by one.
	 * @return void
	 */
	public function tick() {
		$this->count++;
	}
	
	/**
	 * Return the current value for this instance. Starts at zero.
	 * @return int
	 */
	public function current() {
		return $this->count;
	}
}