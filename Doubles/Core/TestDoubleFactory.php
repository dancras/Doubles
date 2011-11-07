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
	
	/** @var array[]string */
	private static $undefinedSubjects = array();
	
	/**
	 * 
	 * 
	 * @param string $nameFormat
	 * @return object
	 */
	public static function create(ISubject $subject, TestDouble $testDouble, $nameFormat) {
		
		if (strpos($nameFormat, '%s') === false) {
			throw new DoublesException('$nameFormat uses sprintf and requires %s in the format');
		}
		
		$subjectName = $subject->getName();		
		$testDoubleName = sprintf($nameFormat, $subjectName);
		
		if (!class_exists($testDoubleName)) {
			self::generateTestDouble($testDoubleName, $subject);
		}
		
		if (in_array($subjectName, self::$undefinedSubjects)) {
			$testDouble->subjectIsUndefined(true);
		}
		
		return new $testDoubleName($testDouble);
	}
	
	private static function generateTestDouble($testDoubleName, ISubject $subject) {
		
		$subjectName = $subject->getName();
		$type = $subject->getType();
		
		$replacements = array(
			'Type' => 'class',
			'TypeName' => $testDoubleName,
			'TypeImplementation' => static::$typeImplementation
		);
		
		if ($type === T_CLASS) {
			self::generateClassIfNew($subjectName);
			$replacements['Extends'] = "extends $subjectName";
		} else if ($type === T_INTERFACE) {
			self::generateInterfaceIfNew($subjectName);
			$replacements['Implements'] = "implements $subjectName";
		}
		
		$replacements['GeneratedMethods'] = self::getRenderedMethods($subject);

		$renderedClass = self::getRenderedType($replacements);
		eval($renderedClass);
	}
	
	private static function generateClassIfNew($subjectName) {
		
		if (!class_exists($subjectName)) {
			
			self::$undefinedSubjects[] = $subjectName;
			
			$renderedClass = self::getRenderedType(array(
					'TypeName' => $subjectName
			));

			eval($renderedClass);
		}
	}
	
	private static function generateInterfaceIfNew($subjectName) {
		
		if (!interface_exists($subjectName)) {
			
			self::$undefinedSubjects[] = $subjectName;
			
			$renderedInterface = self::getRenderedType(array(
					'Type' => 'interface',
					'TypeName' => $subjectName,
					'Constructor' => ''
			));
			
			eval($renderedInterface);
		}
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
	
	private static function getRenderedMethods(ISubject $subject) {

		$methodNames = $subject->getMethodNames();

		$renderedMethods = '';

		foreach ($methodNames as $methodName) {
			
			// These are already defined in the static implementation strings
			if ($methodName === '__construct' || $methodName === '__call') {
				continue;
			}
			
			$reflectMethod = new \ReflectionMethod($subject->getName(), $methodName);

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
		$methodParams = array();
		$params = $method->getParameters();
		foreach ($params as $param) {
				$paramDef = '';
				if ($param->isArray()) {
						$paramDef .= 'array ';
				} elseif ($param->getClass()) {
						$paramDef .= $param->getClass()->getName() . ' ';
				}
				$paramDef .= ($param->isPassedByReference() ? '&' : '') . '$' . $param->getName();
				if ($param->isDefaultValueAvailable()) {
						$default = var_export($param->getDefaultValue(), true);
						if ($default == '') {
							$default = 'null';
						}
						$paramDef .= ' = ' . $default;
				} else if ($param->isOptional()) {
						$paramDef .= ' = null';
				}

				$methodParams[] = $paramDef;
		}
		return implode(',', $methodParams);
	}

}