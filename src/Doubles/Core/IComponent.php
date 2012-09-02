<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Core;

/**
 * A test double is composed of many of these. When a method is called on the
 * test double instance it is passed onto it's components to be handled
 */
interface IComponent
{
    public function whenMethodCalled($methodName, array $arguments);
}

