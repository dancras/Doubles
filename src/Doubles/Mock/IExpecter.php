<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Mock;

/**
 *
 */
interface IExpecter
{
    public function isExpecting($methodName);
}

