<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Interceptor;

use Doubles\Mock\IMock;

interface IInterceptor extends IMock
{
    /**
     * Calls to $methodName will call the provided callback, passing the
     * method name, an array of arguments and the instance being intercepted.
     *
     * @param string  $methodName
     * @param Closure $callback
     */
    public function intercept($methodName, $callback);

    /**
     * @param string $methodName
     */
    public function unintercept($methodName);
}

