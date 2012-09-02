<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

use Doubles\Doubles;

class NoConstructTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorIsSkippedForNoConstruct()
    {
        $instance = Doubles::noConstruct('\Doubles\Test\Dummy');
        $this->assertInstanceOf('\Doubles\Test\Dummy', $instance);
        $this->assertSame('before', $instance->getConstructedValue());

    }
}

