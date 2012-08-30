<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Stub;

use Doubles\Core\IComponent;
use Doubles\Expectation\IExpecter;

/**
 *
 */
class StubComponent implements IExpecter, IComponent
{
    private $stubs = array();

    public function stub($methodName, $returnValue)
    {
        $this->stubs[$methodName] = $returnValue;
    }

    public function unstub($methodName)
    {
        unset($this->stubs[$methodName]);
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->isExpecting($methodName) === false) {
            return;
        }

        if (is_a($this->stubs[$methodName], 'Exception')) {
            return new \Doubles\Core\ExceptionContainer($this->stubs[$methodName]);
        }

        return $this->stubs[$methodName];
    }

    // Stubbing something as null still counts as a stub
    public function isExpecting($methodName)
    {
        return array_key_exists($methodName, $this->stubs);
    }
}

