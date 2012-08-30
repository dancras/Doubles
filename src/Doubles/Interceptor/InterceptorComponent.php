<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

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

