<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Spy;

use \Doubles\Core\DoublesException;

/**
 * Thrown for Spy oneCall method violations
 */
class OneCallException extends DoublesException
{
    public function __construct($methodName, $callCount)
    {
        parent::__construct(
            "One call violation for {$methodName}. Call count was {$callCount}"
        );
    }
}

