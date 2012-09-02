<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Test;

use Doubles\Core\IComponent;

class DummyComponent implements IComponent
{
    public function whenMethodCalled($methodName, array $arguments)
    {
    }

    public function dummy()#
    {
    }
}

