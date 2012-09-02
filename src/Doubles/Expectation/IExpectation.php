<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

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
    public function setUnexpectedMethodCallback(callable $callback);

    /**
     * Calls to the given method wont trigger the callback when unexpected.
     *
     * @param string $methodName
     */
    public function ignore($methodName);

    /**
     * Remove the ignored status of the given method.
     *
     * @param string $methodName
     */
    public function unignore($methodName);
}

