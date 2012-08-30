<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

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
        return null;
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

    public function __construct()
    {
        $this->constructedValue = null;
    }
}

