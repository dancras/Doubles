<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Spy;

use \Doubles\Core\UsageException;

/**
 * Simple incremental counting mechanism with a static function to distribute a
 * new instance to several objects simultaneously
 */
class CallCounter
{
    /** @var int */
    private $count = 0;

    /**
     * Creates a new instance and sets it on all params.
     *
     * All object passed to this method must have the public method setSharedCallCounter
     * or an error will be triggered.
     *
     * @return void
     * @throws \Doubles\Core\UsageException When no arguments are provided.
     */
    public static function shareNew()
    {
        $counter = new self();

        $recipients = func_get_args();

        if (count($recipients) === 0) {
            throw new UsageException('At least one parameter is expected');
        }

        foreach ($recipients as $r) {
            $r->setSharedCallCounter($counter);
        }

    }

    /**
     * Increment the count for this instance by one.
     * @return void
     */
    public function tick()
    {
        $this->count++;
    }

    /**
     * Return the current value for this instance. Starts at zero.
     * @return int
     */
    public function current()
    {
        return $this->count;
    }
}

