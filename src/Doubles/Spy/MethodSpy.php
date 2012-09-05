<?php
/* Copyright (c) 2012, Daniel Howlett. All rights reserved.
 * Dual licensed under the MIT and GPL licenses. */

namespace Doubles\Spy;

use Doubles\Core\FailureException;
use Doubles\Core\UsageException;

/**
 * Used to log actions that occur on a method. Provides a convenient API to the
 * summary of this activity
 */
class MethodSpy
{
    /** @var string */
    private $methodName;

    /** @var int */
    private $callCount = 0;

    /**
     * Public until array de-referencing is more widely available and used
     * @var array
     */
    public $calls = array();

    /**
     * Returns an array of calls for this method.
     *
     * It is significantly preferrable to use other methods which provide shorter syntax
     * and meaningful errors when array indexes are out of bounds.
     *
     * calls()[$callIndex]->args[$argIndex] = n;
     * calls()[$callIndex]->callOrder = n;
     * calls()[$callIndex]->sharedCallOrder = n;
     *
     * @return array
     */
    public function calls()
    {
        return $this->calls;
    }

    /**
     * If call index given then fetches the contents of func_get_args() for that
     * call. Otherwise returns an array of all arguments to all calls.
     *
     * @arg numeric $callIndex [optional]
     * @return array[]mixed
     * @throws \Doubles\Core\FailureException When no method call exists at provided call index
     */
    public function args($callIndex = null)
    {
        if ($callIndex === null) {
            return array_map(
                function ($call) {
                    return $call->args;
                },
                $this->calls
            );
        }

        $this->checkCallIndex($callIndex);

        return $this->calls[$callIndex]->args;
    }

    /**
     * Fetch a specific argument for a specific call made on this method.
     *
     * @arg numeric $callIndex
     * @arg numeric $argIndex
     * @return mixed
     * @throws \Doubles\Core\FailureException When no method call exists at provided call index or
     *                                        no arg exists at provided argIndex
     */
    public function arg($callIndex, $argIndex)
    {
        $this->checkArgIndex($callIndex, $argIndex);

        return $this->calls[$callIndex]->args[$argIndex];

    }

    /**
     * Same as args() with an additional check that the method was called once. I am
     * of the oppinion that this is a very common use case.
     *
     * Since there can only be one call index, that argument is omitted.
     *
     * @return array[]mixed
     * @throws \Doubles\Core\FailureException When method not called exactly one time
     */
    public function oneCallArgs()
    {
        $this->checkOneCall();

        return $this->args(0);

    }

    /**
     * Same as arg() with an additional check that the method was called once.
     *
     * Since there can only be one call index, that argument is omitted.
     *
     * @arg numeric $argIndex
     * @return mixed
     * @throws \Doubles\Core\FailureException When method not called exactly one time or
     *                                        no arg exists at provided argIndex
     */
    public function oneCallArg($argIndex)
    {
        $this->checkOneCall();

        return $this->arg(0, $argIndex);

    }

    /**
     * Find a call with strictly equal arguments to those given and return the
     * call index.
     *
     * @param mixed $argument
     * @param mixed $_ [optional] Zero or more addition arguments
     *
     * @throws \Doubles\Core\FailureException When no call with given arguments.
     *
     * @return int
     */
    public function findArgs()
    {
        $args = func_get_args();

        foreach ($this->calls as $callIndex => $call) {

            if (count($call->args) !== count($args)) {
                continue;
            }

            foreach ($call->args as $argIndex => $arg) {

                if ($args[$argIndex] !== $arg) {
                    break;
                }

                if ($argIndex === count($call->args) - 1) {
                    return $callIndex;
                }

            }
        }

        throw new FailureException('Arguments not found in call history');
    }

    /**
     * Same as findArgs() but returns a fluent interface rather than call index.
     *
     * @param mixed $argument
     * @param mixed $_ [optional] Zero or more addition arguments
     *
     * @throws \Doubles\Core\FailureException When no call with given arguments.
     *
     * @return Doubles\Spy\MethodSpy
     */
    public function checkArgs()
    {
        call_user_func_array(array($this, 'findArgs'), func_get_args());

        return $this;
    }

    /**
     * Returns the number of calls made on this method.
     *
     * @return int
     */
    public function callCount()
    {
        return $this->callCount;
    }

    /**
     * The call order within the spy instance for a specific call to the subject method.
     *
     * @arg numeric $callIndex
     * @return int
     * @throws \Doubles\Core\FailureException When no method call exists at provided call index
     */
    public function callOrder($callIndex)
    {
        $this->checkCallIndex($callIndex);

        return $this->calls[$callIndex]->callOrder;
    }

    /**
     * The call order, as provided by an external CallCounter that can be shared between spies,
     * for a specific call to the subject method
     *
     * @arg numeric $callIndex
     * @return int
     * @throws \Doubles\Core\FailureException When no method call exists at provided call index
     * @throws \Doubles\Core\UsageException When shared call order is not available
     */
    public function sharedCallOrder($callIndex)
    {
        $this->checkCallIndex($callIndex);

        if (!isSet($this->calls[$callIndex]->sharedCallOrder)) {
            throw new UsageException('No shared CallCounter was set when calling ' . $this->methodName);
        }

        return $this->calls[$callIndex]->sharedCallOrder;
    }

    /**
     * @return int
     */
    public function oneCallOrder()
    {
        $this->checkOneCall();

        return $this->callOrder(0);
    }

    /**
     * @return int
     */
    public function oneSharedCallOrder()
    {
        $this->checkOneCall();

        return $this->sharedCallOrder(0);
    }

    /**
     * Log a call to the subject method
     *
     * @arg array $args
     * @arg int $callOrder
     * @arg CallCounter $counter
     */
    public function log(array $args, $callOrder, CallCounter $counter = null)
    {
        $this->callCount++;

        $call = new \stdClass;
        $call->args = $args;
        $call->callOrder = $callOrder;

        if ($counter !== null) {
            $counter->tick();
            $call->sharedCallOrder = $counter->current();
        }

        $this->calls[] = $call;

    }

    /**
     * @arg string
     */
    public function __construct($methodName)
    {
        $this->methodName = $methodName;
    }

    private function checkOneCall()
    {
        if ($this->callCount !== 1) {
            throw new FailureException(
                "One call violation for {$this->methodName}. Call count was {$this->callCount}"
            );
        }
    }

    private function checkCallIndex($callIndex)
    {
        if (!isSet($this->calls[$callIndex])) {
            throw new FailureException('Call index [' . $callIndex .'] out of bounds for ' . $this->methodName);
        }
    }

    private function checkArgIndex($callIndex, $argIndex)
    {
        $this->checkCallIndex($callIndex);

        if (!array_key_exists($argIndex, $this->calls[$callIndex]->args)) {
            throw new FailureException(
                'Arg index [' . $callIndex .'][' . $argIndex . '] out of bounds for ' . $this->methodName
            );
        }

    }
}

