<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Expectation;

/**
 * The api for using test doubles with the expectation component enabled.
 *
 * Extend this interface for any test double interfaces that use expectations.
 */
interface IExpectation
{
    /**
     * Provide a closure that will be called when a method that is not
     * expected by this test double instance is called. The callback
     * will receive the method name and an array of the arguments passed
     * to the method orignally.
     */
    public function setUnexpectedMethodCallback(\Closure $callback);

    /**
     * Expect the given method so that the unexpected method callback will not
     * be called.
     *
     * @param string $methodName
     */
    public function expect($methodName);

    /**
     * Remove an expectation set with expect() for the given method name.
     *
     * @param string $methodName
     */
    public function unexpect($methodName);
}

