<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Expectation;

/**
 * Use this when you have a component that will mark methods as expected.
 */
interface IExpecter
{
    /**
     * Will return true when the given method name is expected.
     *
     * @param string $methodName
     *
     * @return boolean
     */
    public function isExpecting($methodName);
}

