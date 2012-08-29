<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

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

