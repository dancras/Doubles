<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Stub;

use Doubles\Spy\ISpy;
use Doubles\Expectation\IExpectation;

interface IStub extends ISpy, IExpectation
{
    /**
     * Subsequent calls to $methodName will return the provided value. If
     * $returnValue is an Exception or child of, it will be thrown
     *
     * @param string $methodName
     * @param mixed  $returnValue
     */
    public function stub($methodName, $returnValue);

    /**
     * @param string $methodName
     */
    public function unstub($methodName);
}

