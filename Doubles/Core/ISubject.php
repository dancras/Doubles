<?php
namespace Doubles\Core;

/**
 * The subject refers to the class or interface that is being "doubled". This
 * interface encapsulates the information the TestDoubleFactory needs about the
 * Subject to generate and instantiate a class.
 */
interface ISubject {
	
	public function getName();
	
	public function getType();
	
	public function getMethodNames();

}