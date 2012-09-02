<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Core;

/**
 * This is used to communicate to the TestDouble class that we want to throw an
 * Exception, without actually throwing one. The TestDouble can call throwContents
 * when other functionality is complete.
 */
class ExceptionContainer
{
    private $exception;

    public function throwContents()
    {
        throw $this->exception;
    }

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }
}

