<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Stub;

use Doubles\Spy\ISpy;

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

