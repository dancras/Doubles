<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Stub;

use Doubles\Core\IComponent;
use Doubles\Expectation\IExpecter;

class StubComponent implements IExpecter, IComponent
{
    private $stubs = array();

    public function stub()
    {
        $args = func_get_args();

        $returnValue = array_pop($args);

        if (is_array($args[0])) {
            $args = array_merge($args[0], array_slice($args, 1));
        }

        foreach ($args as $methodName) {
            $this->stubs[$methodName] = $returnValue;
        }
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

