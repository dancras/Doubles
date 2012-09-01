<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Interceptor;

use Doubles\Core\IComponent;
use Doubles\Expectation\IExpecter;

/**
 *
 */
class InterceptorComponent implements IComponent, IExpecter
{
    private $instance;

    private $callbacks = array();

    public function intercept($methodName, $callback)
    {
        $this->callbacks[$methodName] = $callback;
    }

    public function unintercept($methodName)
    {
        unset($this->callbacks[$methodName]);
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->isExpecting($methodName) === false) {
            return;
        }

        return $this->callbacks[$methodName]($methodName, $arguments, $this->instance);
    }

    public function isExpecting($methodName)
    {
        return array_key_exists($methodName, $this->callbacks);
    }

    public function __construct($subjectInstance)
    {
        $this->instance = $subjectInstance;
    }
}

