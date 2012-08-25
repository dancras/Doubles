<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * The subject refers to the class or interface that is being "doubled". Encapsulates the information
 * the TestDoubleFactory needs about the Subject to generate and instantiate a double.
 */
class Subject
{
    private $namespace;

    private $name;

    private $type;

    private $fullyQualifiedName;

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFullyQualifiedName()
    {
        return $this->fullyQualifiedName;
    }

    public function getMethodNames()
    {
        $reflectionSubject = new \ReflectionClass($this->getFullyQualifiedName());
        $methods = $reflectionSubject->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);

        $methodNames = array();

        foreach ($methods as $method) {
            $methodNames[] = $method->name;
        }

        return $methodNames;
    }

    public function exists()
    {
        if ($this->type === T_CLASS) {
            return class_exists($this->fullyQualifiedName);
        }

        return interface_exists($this->fullyQualifiedName);
    }

    public function __construct($fullyQualifiedName, $type)
    {
        if ($type !== T_CLASS && $type !== T_INTERFACE) {
            throw new \Exception;
        }

        if (strpos($fullyQualifiedName, '\\') !== 0) {
            throw new \Exception;
        }

        $this->fullyQualifiedName = $fullyQualifiedName;

        $nameSegments = explode('\\', ltrim($this->fullyQualifiedName, '\\'));
        $this->name = array_pop($nameSegments);
        $this->namespace = implode('\\', $nameSegments);

        $this->type = $type;

    }
}

