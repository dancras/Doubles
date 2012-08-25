<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

/**
 *
 */
class InstanceSubject extends Subject
{
    private $instance;

    /**
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param object $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
        parent::__construct('\\' . get_class($instance), T_CLASS);
    }
}

