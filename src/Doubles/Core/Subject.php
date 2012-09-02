<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

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

    public static function fromInstance($object)
    {
        return new static('\\' . get_class($object), T_CLASS);
    }

    public function __construct($fullyQualifiedName, $type)
    {
        if ($type !== T_CLASS && $type !== T_INTERFACE) {
            throw new UsageException('$type must be T_CLASS or T_INTERFACE');
        }

        if (strpos($fullyQualifiedName, '\\') !== 0) {
            throw new UsageException('$fullyQualifiedName must start with \\');
        }

        $this->fullyQualifiedName = $fullyQualifiedName;

        $nameSegments = explode('\\', ltrim($this->fullyQualifiedName, '\\'));
        $this->name = array_pop($nameSegments);
        $this->namespace = implode('\\', $nameSegments);

        $this->type = $type;

    }
}

