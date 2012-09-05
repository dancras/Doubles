<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Mock;

use Doubles\Core\IComponent;
use Doubles\Expectation\IExpecter;

/**
 *
 */
class MockComponent implements IExpecter, IComponent
{
    private $callbacks = array();

    public function mock($methodName, $callback)
    {
        $this->callbacks[$methodName] = $callback;
    }

    public function unmock($methodName)
    {
        unset($this->callbacks[$methodName]);
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->isExpecting($methodName) === false) {
            return;
        }

        return call_user_func($this->callbacks[$methodName], $methodName, $arguments);
    }

    public function isExpecting($methodName)
    {
        return array_key_exists($methodName, $this->callbacks);
    }
}

