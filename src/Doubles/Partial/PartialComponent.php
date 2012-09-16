<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Partial;

use Doubles\Core\IComponent;
use Doubles\Core\PartialInterface;
use Doubles\Expectation\ExpectationComponent;

/**
 * Acts as a proxy to the original method but will not trigger the original
 * method if some other component in the test double is handling it.
 */
class PartialComponent implements IComponent
{
    /** @var \Doubles\Expectation\ExpectationComponent */
    private $expectationComponent;

    /**
     * Use reflection to copy instance properties from the test subject to the
     * test double partial.
     *
     * @param object $instance
     * @param object $partial
     */
    public static function mergeSubjectToPartial($instance, $partial)
    {
        $rInstance = new \ReflectionObject($instance);
        $properties = $rInstance->getProperties();

        foreach ($properties as $p) {

            if ($p->isStatic()) {
                continue;
            }

            $originalAccess = $p->isPublic();
            $p->setAccessible(true);
            $p->setValue($partial, $p->getValue($instance));
            $p->setAccessible($originalAccess);
        }
    }

    public function whenMethodCalled($methodName, array $arguments)
    {
        if ($this->expectationComponent->isMethodExpected($methodName)) {
            return;
        }

        return new PartialInterface;
    }

    public function __construct(
        ExpectationComponent $expectationComponent
    ) {
        $this->expectationComponent = $expectationComponent;
    }
}

