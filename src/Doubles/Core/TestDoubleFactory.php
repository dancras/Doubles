<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * 
 */
class TestDoubleFactory {
	
	public static $typeImplementation = '
	private $testDouble;
	
	public function __call($methodName, $arguments) {

		return $this->testDouble->whenUndefinedMethodCalled($methodName, $arguments);

	}
';
	
	public static $methodImplementation = '
	public function %MethodName%(%Parameters%) {

		return $this->testDouble->whenSubjectMethodCalled("%MethodName%", func_get_args());

	}';
	
	public static $constructorImplementation = '
	public function __construct(\Doubles\Core\TestDouble $testDouble) {
		$this->testDouble = $testDouble;
	}';
	
	const TYPE_TEMPLATE = '
%Type% %TypeName% %Extends% %Implements% {

%TypeImplementation%

%Constructor%

%GeneratedMethods%

}';

	/**
	 * Maintains a list of subjects that were not defined when first passed into the factory
	 * 
	 * @var array
	 */
	private static $undefinedSubjects = array();
	
	/**
	 * 
	 * @param \Doubles\Core\Subject $subject
	 * @param \Doubles\Core\TestDouble $testDouble
	 * @param string $nameFormat
	 * @return object
	 * @throws DoublesException
	 */
	public static function create(Subject $subject, TestDouble $testDouble, $nameFormat) {
		
		if (strpos($nameFormat, '%s') === false) {
			throw new DoublesException('$nameFormat uses sprintf and requires %s in the format');
		}
		
		$testDoubleName = sprintf($nameFormat, $subject->getName());
		$fullyQualifiedTestDoubleName = $subject->getNamespace() . '\\' .  $testDoubleName;
		
		if (!$subject->exists()) {
			self::evalSubject($subject);
			self::$undefinedSubjects[] = $subject->getFullyQualifiedName();
		}
		
		if (in_array($subject->getFullyQualifiedName(), self::$undefinedSubjects)) {
			$testDouble->subjectIsUndefined(true);
		}

		if (!class_exists($fullyQualifiedTestDoubleName)) {
			self::evalTestDouble($testDoubleName, $subject);
		}
		
		return new $fullyQualifiedTestDoubleName($testDouble);
	}
	
	private static function evalTestDouble($testDoubleName, Subject $subject) {
		
		$subjectName = $subject->getName();
		$type = $subject->getType();
		$namespace = $subject->getNamespace();
		
		$replacements = array(
			'Type' => 'class',
			'TypeName' => $testDoubleName,
			'TypeImplementation' => static::$typeImplementation
		);
		
		if ($type === T_CLASS) {
			$replacements['Extends'] = "extends $subjectName";
		} else {
			$replacements['Implements'] = "implements $subjectName";
		}
		
		$replacements['GeneratedMethods'] = self::getRenderedMethods($subject);

		$renderedTestDouble = self::getRenderedTypeWithNamespace(
			self::getRenderedType($replacements),
			$namespace
		);
		
		eval($renderedTestDouble);
	}
	
	private static function evalSubject(Subject $subject) {
		
		$replacements = array(
			'TypeName' => $subject->getName()
		);
		
		if ($subject->getType() === T_INTERFACE) {
			$replacements['Type'] = 'interface';
			$replacements['Constructor'] = '';
		}
		
		$renderedType = self::getRenderedTypeWithNamespace(
			self::getRenderedType($replacements),
			$subject->getNamespace()
		);
		
		eval($renderedType);

	}
	
	private static function getRenderedTypeWithNamespace($renderedType, $namespace) {
		
		if ($namespace === "") {
			return $renderedType;
		}
		
		return "
namespace {$namespace} {
{$renderedType}
}
";
	}

	private static function getRenderedType(array $replacements) {
		
		$renderedType = self::TYPE_TEMPLATE;
		
		$mergedReplacements = array_merge(
				array(
						'Type' => 'class',
						'TypeName' => '',
						'Constructor' => self::$constructorImplementation,
						'Extends' => '',
						'Implements' => '',
						'TypeImplementation' => '',
						'GeneratedMethods' => ''),
				$replacements);
		
		foreach ($mergedReplacements as $placeholder => $replacement) {
			$renderedType = str_replace("%{$placeholder}%", $replacement, $renderedType);
		}

		return $renderedType;
	}
	
	private static function getRenderedMethods(Subject $subject) {

		$methodNames = $subject->getMethodNames();

		$renderedMethods = '';

		foreach ($methodNames as $methodName) {
			
			// These are already defined in the static implementation strings
			if ($methodName === '__construct' || $methodName === '__call') {
				continue;
			}
			
			$reflectMethod = new \ReflectionMethod($subject->getFullyQualifiedName(), $methodName);

			$renderedParameters = self::getRenderedParameters($reflectMethod);
			$renderedMethods .= self::getRenderedMethod($methodName, $renderedParameters);

		}
		
		return $renderedMethods;

	}
	
	private static function getRenderedMethod($methodName, $renderedParameters) {
		$renderedMethod = static::$methodImplementation;
		$renderedMethod = str_replace('%MethodName%', $methodName, $renderedMethod);
		$renderedMethod = str_replace('%Parameters%', $renderedParameters, $renderedMethod);
		return $renderedMethod;
	}
	
	private static function getRenderedParameters(\ReflectionMethod $method) {
		
		$renderedParameters = array();
		
		$parameters = $method->getParameters();
		
		foreach ($parameters as $parameter) {
				$renderedParameters[] = self::getRenderedParameter($parameter);
		}
		
		return implode(',', $renderedParameters);
	}
	
	private static function getRenderedParameter(\ReflectionParameter $parameter) {

		$typeHint = '';
		$reference = '';
		$name = $parameter->getName();
		$default = '';

		if ($parameter->isArray()) {
				$typeHint = 'array ';
		} elseif ($parameter->getClass()) {
				$typeHint = $parameter->getClass()->getName() . ' ';
		}

		if ($parameter->isPassedByReference()) {
			$reference = '&';
		}

		if ($parameter->isDefaultValueAvailable()) {

				$default = var_export($parameter->getDefaultValue(), true);

				if ($default == '') {
					$default = 'null';
				}

				$default = ' = ' . $default;

		} else if ($parameter->isOptional()) {

				$default = ' = null';

		}

		return $typeHint . $reference . '$' . $name . $default;
	}

}