<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Partial;

use Doubles\Core\IComponent;
use Doubles\Expectation\ExpectationComponent;

/**
 *
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

        return call_user_func_array(array($this->instance, $methodName), $arguments);
    }

    public function __construct(
        $subjectInstance,
        ExpectationComponent $expectationComponent
    ) {
        $this->instance = $subjectInstance;
        $this->expectationComponent = $expectationComponent;
    }
}

