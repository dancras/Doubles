<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Partial;

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

