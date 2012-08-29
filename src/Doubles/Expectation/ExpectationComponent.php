<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Expectation;

use Doubles\Core\IComponent;

/**
 * Provides functionality to track which methods are expected for a test double.
 * A callback can be set to handle unexpected method usage.
 */
class ExpectationComponent implements IComponent, IExpectation, IExpecter
{
    private $expecters = array();

    private $unexpectedMethodCallback;

    private $expected = array();

    public function setUnexpectedMethodCallback(\Closure $callback)
    {
        $this->unexpectedMethodCallback = $callback;
    }

    public function expect($methodName)
    {
        $this->expected[$methodName] = $methodName;
    }

    public function unexpect($methodName)
    {
        unset($this->expected[$methodName]);
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->isMethodExpected($methodName)) {
            return;
        }

        call_user_func($this->unexpectedMethodCallback, $methodName, $arguments);
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
        $this->addExpecter($this);

        $this->unexpectedMethodCallback = function () {
        };

    }
}

