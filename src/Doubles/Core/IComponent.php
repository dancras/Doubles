<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 * A test double is composed of many of these. When a method is called on the
 * test double instance it is passed onto it's components to be handled
 */
interface IComponent
{
    public function whenMethodCalled($methodName, array $arguments);
}

