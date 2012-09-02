<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Spy;

use Doubles\Core\IComponent;

/**
 *
 */
class SpyComponent implements IComponent
{
    private $methodSpies = array();

    private $callCount = 0;

    private $callCounter = null;

    public function spy($methodName)
    {
        if (isSet($this->methodSpies[$methodName])) {
            return $this->methodSpies[$methodName];
        }

        return new MethodSpy($methodName);
    }

    public function callCount()
    {
        return $this->callCount;
    }

    public function setSharedCallCounter(CallCounter $counter)
    {
        $this->callCounter = $counter;
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        $this->callCount++;

        if (!isSet($this->methodSpies[$methodName])) {
            $this->methodSpies[$methodName] = new MethodSpy($methodName);
        }

        $this->methodSpies[$methodName]->log($arguments, $this->callCount, $this->callCounter);

    }
}

