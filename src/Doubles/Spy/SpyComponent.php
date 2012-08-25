<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

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

