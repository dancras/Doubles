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
class ExpectationComponent implements IComponent, IExpectation
{
    private $expecters = array();

    private $unexpectedMethodCallback;

    private $ignored = array();

    public function setUnexpectedMethodCallback(\Closure $callback)
    {
        $this->unexpectedMethodCallback = $callback;
    }

    /**
     * Use this to stop the unexpected callback for the given method.
     *
     * @param string $methodName
     */
    public function ignore($methodName)
    {
        $this->ignored[$methodName] = $methodName;
    }

    public function unignore($methodName)
    {
        unset($this->ignored[$methodName]);
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if (
            $this->isMethodIgnored($methodName) ||
            $this->isMethodExpected($methodName)
        ) {
            return;
        }

        call_user_func($this->unexpectedMethodCallback, $methodName, $arguments);
    }

    /**
     * Returns true when a method has been given to ignore(). Differs from
     * expected; expected implies some component is expecting to provide an
     * implementation for the method.
     *
     * @param string $methodName
     * @return boolean
     */
    public function isMethodIgnored($methodName)
    {
        return in_array($methodName, $this->ignored);
    }

    /**
     * Returns true when one of the registered IExpecters is expecting the given
     * method.
     *
     * @param string $methodName
     * @return boolean
     */
    public function isMethodExpected($methodName)
    {
        foreach ($this->expecters as $component) {

            if ($component->isExpecting($methodName)) {
                return true;
            }
        }

        return false;
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

