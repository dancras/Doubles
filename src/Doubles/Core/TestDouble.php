<?php
/* Copyright (c) 2011, Daniel Howlett. All rights reserved.
 * Released under a new BSD license.
 * https://github.com/dancras/Doubles/blob/master/LICENSE */

namespace Doubles\Core;

use \SplStack;

/**
 * Acts as a bridge between the generated class and the functionality that
 * can be composed onto instances of this.
 */
class TestDouble
{
    /**
     * Methods that are not defined on the subject will behave as if they were
     * when rapid prototyping is enabled.
     *
     * Subjects that are undefined always use rapid prototyping mode
     *
     * @var boolean
     */
    public static $isRapidPrototypingEnabled = false;

    /** @var SplStack */
    private $components;

    /** @var boolean */
    private $isSubjectUndefined = false;

    public function whenSubjectMethodCalled($methodName, $arguments)
    {
        $returnedValues = array();

        foreach ($this->components as $component) {
            $returnedValues[] = $component->whenMethodCalled($methodName, $arguments);
        }

        $value = $this->findFirstNonNull($returnedValues);

        if (is_a($value, '\Doubles\Core\ExceptionContainer')) {
            $value->throwContents();
        }

        return $value;
    }

    private function findFirstNonNull(array $input)
    {
        foreach ($input as $value) {

            if ($value !== null) {
                return $value;
            }

        }

        return null;
    }

    public function whenUndefinedMethodCalled($methodName, $arguments)
    {
        foreach ($this->components as $component) {
            if (method_exists($component, $methodName)) {
                return call_user_func_array(array($component, $methodName), $arguments);
            }
        }

        if (self::$isRapidPrototypingEnabled === true || $this->isSubjectUndefined === true) {
            return $this->whenSubjectMethodCalled($methodName, $arguments);
        }

        throw new DoublesException(
            $methodName . ' does not exist on the test subject. Perhaps you want rapid prototyping enabled?'
        );

    }

    public function addComponent(IComponent $component)
    {
        $this->components->push($component);
    }

        /**
         * Set to true when the subject class or interface of this test double
         * has not been implemented yet.
         *
         * @param boolean $setting
         */
    public function subjectIsUndefined($setting)
    {
        $this->isSubjectUndefined = $setting;
    }

    public function __construct()
    {
        $this->components = new SplStack();
    }
}

