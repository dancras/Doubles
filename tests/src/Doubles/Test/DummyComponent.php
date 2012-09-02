<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

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

