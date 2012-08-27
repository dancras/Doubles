<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Partial;

use Doubles\Mock\IExpecter;
use Doubles\Core\IComponent;

/**
 *
 */
class InterceptorComponent implements IExpecter, IComponent
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

    public function __construct(\Doubles\Core\InstanceSubject $subject)
    {
        $this->instance = $subject->getInstance();
    }
}

