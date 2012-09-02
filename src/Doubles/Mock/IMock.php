<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Mock;

use Doubles\Stub\IStub;

interface IMock extends IStub
{
    /**
     * Calls to $methodName will call the provided callback, passing the
     * method name and an array of arguments
     *
     * @param string  $methodName
     * @param Closure $callback
     */
    public function mock($methodName, $callback);

    /**
     * @param string $methodName
     */
    public function unmock($methodName);
}

