<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Test;

/**
 * Some features of the doubles framework operate on existing classes. This
 * dummy class is used to test those.
 */
class Dummy
{
    private $constructedValue = 'before';

    private $value;

    protected function getProtectedValue()
    {
        return 'bar';
    }

    /**
     * Static functions are currently ignored. Including this ensures they don't
     * cause any bugs when included.
     */
    public static function getStaticValue()
    {
    }

    /**
     * See getStaticValue()
     */
    final public function getFinalValue()
    {
    }

    public function getConstructedValue()
    {
        return $this->constructedValue;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getFixedValue()
    {
        return 'fixed';
    }

    public function foo()
    {
    }

    public function getOtherMethod()
    {
        return $this->getFixedValue();
    }

    public function __construct()
    {
        $this->constructedValue = null;
    }
}

