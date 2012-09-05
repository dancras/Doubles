<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Stub;

use Doubles\Spy\ISpy;
use Doubles\Expectation\IExpectation;

interface IStub extends ISpy, IExpectation
{
    /**
     * Subsequent calls to given methods will return the provided return value.
     * If the return value is an Exception or child of, it will be thrown
     *
     * @param string|array $methodName A method name or array of method names
     * @param string $_ [optional] Zero or more method names
     * @param mixed $returnValue
     */
    public function stub();

    /**
     * @param string $methodName
     */
    public function unstub($methodName);
}

