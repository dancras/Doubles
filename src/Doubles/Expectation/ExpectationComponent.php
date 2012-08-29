<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Expectation;

use Doubles\Core\IComponent;

/**
 *
 */
class ExpectationComponent implements IComponent, IExpecter
{
    private $expecters = array();

    private $unexpectedMethodCallback;

    public function setUnexpectedMethodCallback(\Closure $callback)
    {
        $this->unexpectedMethodCallback = $callback;
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->isMethodExpected($methodName)) {
            return;
        }

        $callback = $this->unexpectedMethodCallback;
        $callback($methodName, $arguments);
    }

    public function isMethodExpected($methodName)
    {
        foreach ($this->expecters as $component) {

            if ($component->isExpecting($methodName)) {
                return true;
            }

        }

        return false;
    }

    public function isExpecting($methodName)
    {
        return isset($this->expected[$methodName]);
    }

    public function addExpecter(IExpecter $expecter)
    {
        $this->expecters[] = $expecter;
    }

    public function __construct()
    {
        $this->unexpectedMethodCallback = function () {
        };
    }
}

