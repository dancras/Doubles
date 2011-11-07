Doubles
=======

A test doubles framework which is simple, logical, powerful and portable.

Requires PHP 5.3+.

Released under a New BSD License.

Feedback can be left at http


Getting Started
---------------

Download the source and place the doubles folder somewhere accessible by your
project.

	require_once /path/to/doubles/bootstrap.php

Alternatively, file and folder names match the namespacing so it will be
compatible with a simple autoloader (not provided).

No changes to your unit testing framework are required for integration.


Shortcomings
------------

 *   If your classes have matching methods to the chosen test double, there is
     currently no way (or planned feature) to access the test double method.

 *   There is no support for partial doubles.

 *   There is no support for protected methods.


Taster
------

Below is an incredibly contrived example of doubles being used with PHPUnit. It
hopefully demonstrates the simplicity of performing regular tasks eg. stubbing,
checking arguments, and also the power and flexibility granted by the mock method.

	use \Doubles\Mock;

	class ExampleTest extends PHPUnit_Framework_TestCase {

	  public function testThatShowsMockStubAndSpy() {

	    $double = Mock::fromClass('SomeClass');

	    $double->mock('doSomething', function ($methodName, $arguments) use ($double) {

	      if ($double->spy('doSomething')->callCount() === 2) {
	        return $arguments[0];
	      }

	      return $methodName;

	    });

	    $double->stub('returnSomething', 'stubValue');

	    $this->assertSame('doSomething', $double->doSomething('firstValue'));
	    $this->assertSame('secondValue', $double->doSomething('secondValue'));

	    $this->assertSame('firstValue', $double->spy('doSomething')->arg(0, 0));

	    $this->assertSame('stubValue', $double->returnSomething());
	  }

	}


Reference
---------

If you prefer to read code, the unit tests for doubles cover all of the basic use
cases. This reference will provide examples with some reasoning behind them.


### Spies

Tracks any interactions with an object and provides a clean API to assert against.

#### Arguments

Having direct access to the arguments allows you to interact with them with code,
to perform whatever assertions you need. Furthermore you can call methods on the
value which is particularly useful when it was instantiated by the system under
test, eg. a method that returns a value object.

	// Will return the arguments for the nth $double->methodName() use as an array.
	$double->spy('methodName')->args($n);

	// Will return the nth argument for the nth call.
	$double->spy('methodName')->arg($n, $n);

#### One Call Methods

Very often when spying methods you will want to ensure it is only being called
once to ensure there are no unexpected side-effects in your code. Rather than
asserting against the call count repeatedly, a one call version is available.

	/* Will return an array of arguments for the first and only call. If there is
	 * more than one call tracked on this method, a \Doubles\Spy\OneCallException
	 * will be thrown, causing the test to fail */
	$double->spy('methodName')->oneCallArgs();

	// As above, but returning the nth argument only
	$double->spy('methodName')->oneCallArg($n);

#### Call Counts

	// Will return the total calls across all methods for the instance
	$double->callCount();

	// Will return the total calls of methodName() only for the instance
	$double->spy('methodName')->callCount();

#### Call Order

Tests involving call order are often very fragile. By having access to the call
order value, you can assert that a method is called before another method,
without saying exactly when it will be called. Using the shared call order
below, this can even be done between separate instances.

	/* Will return the order that the nth call to this method was made against all
	 * calls to all methods on this instance */
	$double->spy('methodName')->callOrder($n);

	// See One Call Methods above
	$double->spy('methodName')->oneCallOrder();

#### Shared Call Order

Occasionally you need to compare the call order between instances to ensure that
methods that depend on other methods are happening in order.

	/* Accepts any number of arguments and distributes a call counter between them.
	 * An instance may only have one call counter configured at a time. */
	CallCounter::shareNew($double, $double2, $doubleN);

	/* Will return the order that the nth call to this method was made between all
	 * calls to all methods on all instances sharing the same call counter */
	$double->spy('methodName')->sharedCallOrder($n);

	// See One Call Methods above
	$double->spy('methodName')->oneSharedCallOrder($n);


### Mocks

The mock interface combines spying, stubbing and mocking so instances created as
mocks can use all of the methods in the Spy API.

#### Stubbing

	// Subsequent calls to $double->methodName() will return 'someValue'
	$double->stub('methodName', 'someValue');

	/* $double->methodName() will throw the provided Exception. Spy behaviour
	 * is not disrupted by this. */
	$double->stub('methodName', new Exception);

#### Mocking

	/* Calls to methodName will be forwarded to the provided closure.
	 * 
	 * Performing assertions within this callback is not recommended because
         * if your code fails to reach it, the test will pass. Instead, pass any
	 * data required for assertions out of the closure using referenced
	 * variables. */
	$double->mock('methodName', function ($methodName, $arguments) use (&$m) {
	  $m = $methodName;
	}

#### Expectations

By default, calling methods that have not been defined as either a mock or stub
will trigger no errors, and will be tracked by the spy. However if you require
some action when unexpected methods are called, there is a method available.

	/* Perform a callback when a method that has not been mocked or stubbed. By
	 * doing this in a closure you can be logically selective about how you handle
	 * expectations and their violations */
	$double->setUnexpectedMethodCallback(function ($methodName, $arguments) {
		throw new Exception('Unexpected method call on ' . methodName);
	});


### Rapid Prototyping

By default, when using a test double for a defined type, an exception will be
thrown if a method that doesn't exist on the original class or the test
double API is called.

If the type doesn't exist then it is considered to be rapid prototyping (because
having to define all your class signatures up front gets tedious). In this mode
any methods can be used. When you define the class or interface, tests will fail
until you complete it's signature.

I find this behaviour very effective, however if it is not desired functionality then
rapid prototyping mode can be forced on, meaning methods outside the class or
interface signature can be used freely.

	\Doubles\Core\TestDouble::$isRapidPrototypingEnabled = true;
