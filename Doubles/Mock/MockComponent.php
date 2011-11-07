<?php
namespace Doubles\Mock;

/**
 * 
 */
class MockComponent implements IExpecter, \Doubles\Core\IComponent {
	
	private $callbacks = array();
	
	public function mock($methodName, $callback) {
		$this->callbacks[$methodName] = $callback;
	}
	
	public function unmock($methodName) {
		unset($this->callbacks[$methodName]);
	}
	
	public function whenMethodCalled($methodName, array $arguments) {

		if ($this->isExpecting($methodName) === false) {
			return;
		}
		
		return $this->callbacks[$methodName]($methodName, $arguments);
	}

	public function isExpecting($methodName) {
		return array_key_exists($methodName, $this->callbacks);
	}

}