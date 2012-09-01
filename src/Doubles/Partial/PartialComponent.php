<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

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

