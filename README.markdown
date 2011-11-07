Doubles
=======

A test doubles framework which is simple, logical, powerful and portable.

Requires PHP 5.3+.

Released under a New BSD License.

Feedback can be left at http://www.dancras.co.uk


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


Reference
---------

If you prefer to read code, the unit tests for doubles cover all of the basic use
cases. This reference will provide examples with some reasoning behind them.


### Spies

Tracks any interactions with an object and provides a clean API to assert against.

	$myObject = Spy::fromClass('MyClass');

	is_a($myObject, 'MyClass'); // true

Creating a test double will define a new class extending the original and return
an instance of it. This can be passed to the object under test.

#### Arguments

During execution our spy has the following actions performed on it:

	$myObject->give('first', 1);

	$myObject->give('second', 2);

	$myObject->doSomething();

Arguments passed to the spy can be retrieved:

	$myObject->spy('give')->args(0); // array('first', 1)

	$myObject->spy('give')->args(1); // array('second', 2)

Or more directly:

	$myObject->spy('give')->args(0, 1); // 'first'

	$myObject->spy('give')->args(0, 1); // 1

Arguments can be tested with the full power of php. A simple example:

	$arg = $myObject->spy('give')->args(0, 1);

	$this->assertTrue( $arg < 10 && $arg % 2 === 0 ); // fail

#### Call Counts

Continuing with the instance from the previous examples, we can also determine
the total call count of all methods:

	$myObject->callCount(); // 3

And for a specific method:

	$myObject->spy('give')->callCount(); // 2

#### One Call Methods

Using doubles, I found myself doing this very often:

	$this->assertSame(1, $myObject->spy('doSomething')->callCount());

So I've added a one call variant which will throw an exception is the method in
question has not received exactly one call.

	$myObject->spy('give')->oneCallArgs(); // throws \Doubles\Spy\OneCallException

	$myObject->spy('doSomething')->oneCallArgs(); // array()

	$myObject->spy('foo')->oneCallArgs(); // throws \Doubles\Spy\OneCallException

Since it requires exactly one call there is no need to supply a call index.

	$myObject->spy('give')->oneCallArg(0); // 'first'

#### Call Order

Still using the same example, we can also determine the order a call to a method
was made. Call order begins at 1.

	$myObject->spy('give')->callOrder(0); // 1

	$myObject->spy('give')->callOrder(1); // 2

	$myObject->spy('doSomething')->callOrder(0); // 3

By doing so we can assert that methods are called in the expected order.

	$this->assertGreaterThan($myObject->spy('give')->callOrder(1), $myObject->spy('doSomething')->oneCallOrder()); // pass

Notice how oneCallOrder() was used, performing the boilerplate one call check.

#### Shared Call Order

Occasionally you need to compare the call order between instances. Assume all
objects in this example have been created as spies.

	CallCounter::shareNew($pizza, $waiter, $customer);

The \Doubles\Spy\CallCounter distributes a shared call counter. The following
actions are performed on our objects:

	$pizza->cook();

	$customer->eat($pizza);
	
	$waiter->take($pizza);	

Clearly these must occur in a specific order.

	$this->assertGreaterThan($pizza->spy('cook')->oneSharedCallOrder(), $waiter->spy('take')->sharedCallOrder(0)); // pass

	$this->assertGreaterThan($waiter->spy('take')->sharedCallOrder(0), $customer->spy('eat')->sharedCallOrder(0)); // fail

Our impatient customer appears to be helping him/her self and so our test fails.
Notice again the oneSharedCallOrder variant, ensuring our pizza is not burnt.


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
