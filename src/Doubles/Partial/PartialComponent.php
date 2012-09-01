<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Partial;

use Doubles\Core\IComponent;
use Doubles\Expectation\ExpectationComponent;

/**
 * Acts as a proxy to the original method but will not trigger the original
 * method if some other component in the test double is handling it.
 */
class PartialComponent implements IComponent
{
    private $instance;

    /** @var \Doubles\Expectation\ExpectationComponent */
    private $expectationComponent;

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->expectationComponent->isMethodExpected($methodName)) {
            return;
        }

        $method = new \ReflectionMethod($this->instance, $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->instance, $arguments);
    }

    public function __construct(
        $subjectInstance,
        ExpectationComponent $expectationComponent
    ) {
        $this->instance = $subjectInstance;
        $this->expectationComponent = $expectationComponent;
    }
}

