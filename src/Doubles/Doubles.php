<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles;

use Doubles\Expectation\ExpectationComponent;
use Doubles\Interceptor\InterceptorComponent;
use Doubles\Partial\PartialComponent;
use Doubles\Stub\StubComponent;

/**
 * Provides static methods to create composed instances of test doubles
 */
class Doubles
{
    /**
     * @param string $className
     * @return \Doubles\Mock\IMock
     */
    public static function fromClass($className)
    {
        return self::create($className, T_CLASS);
    }

    /**
     * @param string $interfaceName
     * @return \Doubles\Mock\IMock
     */
    public static function fromInterface($interfaceName)
    {
        return self::create($interfaceName, T_INTERFACE);
    }

    /**
     * Create an instance of the provided type name without calling the
     * original constructor.
     *
     * @param string $fullyQualifiedTypeName
     * @return $fullyQualifiedTypeName
     */
    public static function noConstruct($fullyQualifiedTypeName)
    {
        return Core\TestDoubleFactory::create(
            new Core\NoConstructSubject($fullyQualifiedTypeName, T_CLASS),
            new Core\TestDouble,
            'NoConstruct%s'
        );
    }

    /**
     * @return \Doubles\Interceptor\IInterceptor
     */
    public static function partial($object)
    {
        $subject = Core\Subject::fromInstance($object);

        $testDouble = new Core\TestDouble;

        $testDouble->addComponent(new Spy\SpyComponent);

        $expectationComponent = new ExpectationComponent;
        $stubComponent = new StubComponent;
        $mockComponent = new Mock\MockComponent;
        $interceptorComponent = new InterceptorComponent($object);

        $expectationComponent->addExpecter($stubComponent);
        $expectationComponent->addExpecter($mockComponent);
        $expectationComponent->addExpecter($interceptorComponent);

        $testDouble->addComponent(new PartialComponent($object, $expectationComponent));
        $testDouble->addComponent($expectationComponent);
        $testDouble->addComponent($stubComponent);
        $testDouble->addComponent($mockComponent);
        $testDouble->addComponent($interceptorComponent);

        return Core\TestDoubleFactory::create(
            $subject,
            $testDouble,
            'Partial%s'
        );
    }

    private static function create($subjectName, $type)
    {
        $subject = new Core\Subject($subjectName, $type);
        $testDouble = new Core\TestDouble;

        $expectationComponent = new ExpectationComponent;
        $stubComponent = new StubComponent;
        $mockComponent = new Mock\MockComponent;

        $expectationComponent->addExpecter($stubComponent);
        $expectationComponent->addExpecter($mockComponent);

        $testDouble->addComponent($expectationComponent);
        $testDouble->addComponent($stubComponent);
        $testDouble->addComponent($mockComponent);
        $testDouble->addComponent(new Spy\SpyComponent);

        return Core\TestDoubleFactory::create($subject, $testDouble, 'Mock%s');
    }
}

