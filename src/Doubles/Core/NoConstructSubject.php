<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Core;

/**
 * Creating a test double from this class will create an instance with all
 * methods untouched, without calling the original constructor.
 */
class NoConstructSubject extends Subject
{
    public function getMethodNames()
    {
        return array();
    }
}

