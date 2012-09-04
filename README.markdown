Doubles
=======

A test doubles library which is simple, logical, powerful and portable.

Requires PHP 5.3+.

No changes to your unit testing framework are required for integration.

Copyright (c) 2012 Daniel Howlett
Dual licensed under the MIT and GPL licenses.

Feedback can be left at http://www.dancras.co.uk

[![Build Status](https://secure.travis-ci.org/dancras/Doubles.png)](http://travis-ci.org/dancras/Doubles)


Installation with composer
--------------------------

To install from packagist, add the following to your composer.json:

    {
        "minimum-stability": "dev",
        "require": {
            "dancras/doubles": "*"
        }
    }

Don't forget to include vendor/autoload.php in your code.


Known issues
------------

 *   If your classes have matching methods to the chosen test double, there is
     currently no way to access the test double method.


Reference
---------

Add the following:

    use Doubles\Doubles;


### Full test doubles

Create test doubles with all methods of subject replaced:

    $double = Doubles::fromClass('\MyClass');

    $double = Doubles::fromInterface('\MyInterface');

Doubles will create the subject if it does not exist yet.


### Partial test doubles

Methods of a partial test double are unaffected until they are stubbed, mocked
or intercepted. They are created from an instance of the subject.

    $subject = new \Doubles\Test\Dummy;

    $double = Doubles::partial($subject);

It might be necessary to skip the constructor of a subject:

    $subject = Doubles::noConstruct('\Doubles\Test\Dummy');


### Spies

Provide access to the history of interactions with a test double, including
unaffected methods of partial test doubles. A graph service test double might
have the following actions performed on it:

    $double->plot(0, 5);

    $double->plot(2, 6);

    $double->setLineColour('red');

    $double->render();

We can interrogate the test double after the code is run:

    $double->spy('plot')->args(0); // array(0, 5)

    $double->spy('plot')->arg(1, 0); // 2

    $double->spy('plot')->callCount(); // 2

    $double->callCount(); // 4


#### One Call

When a method is expecting one call we can avoid superfluous assertions by using
the one call variant. Notice you can omit the call index when using the one call
variant.

    $double->spy('setLineColour')->oneCallArgs(); // array('red')

    $double->spy('setLineColour')->oneCallArgs(0); // 'red'

A one call method will throw an exception if the method has not received exactly
one call.

    $double->spy('plot')->oneCallArgs(); // throws \Doubles\Spy\OneCallException

    $double->spy('foo')->oneCallArgs(); // throws \Doubles\Spy\OneCallException


#### Call Order

Starts from one. Can be used to assert that methods are called in the expected
order.

    $double->spy('plot')->callOrder(0); // 1

    $double->spy('plot')->callOrder(1); // 2

The one call variant also works for call order:

    $double->spy('render')->oneCallOrder(0); // 4

The following code asserts that render is called last and only once:

    $this->assertSame(
        $double->callCount(),
        $double->spy('render')->oneCallOrder(0)
    ); // pass


#### Shared Call Order

Occasionally you need to compare the call order between instances. The
Doubles\Spy\CallCounter::shareNew() method distributes a shared call counter.
Assume all objects in this example have been created as test doubles:

    use Doubles\Spy\CallCounter;

    CallCounter::shareNew($pizza, $waiter, $customer);

The following actions are incorrectly performed on our objects. Our impatient
customer seems to be helping him or herself:

    $pizza->cook();

    $customer->eat($pizza);

    $waiter->take($pizza);

Using the shared call order we can catch this error in our tests. The pizza
must be cooked before the waiter takes it:

    $this->assertGreaterThan(
        $pizza->spy('cook')->oneSharedCallOrder(),
        $waiter->spy('take')->sharedCallOrder(0)
    ); // pass

The waiter must take the pizza before the customer eats it:

    $this->assertGreaterThan(
        $waiter->spy('take')->sharedCallOrder(0),
        $customer->spy('eat')->sharedCallOrder(0)
    ); // fail

Notice again the one call variant, ensuring our pizza is not burnt.


### Stubs

    $double->stub('foo', 'bar');

    $double->foo(); // 'bar'

Stubs can also throw exceptions:

    $double->stub('boom', new EndOfTheWorldException);

    $double->boom(); // throws EndOfTheWorldException

To actually return an exception you need to use a mock.


### Mocks

Mocking is the most versatile way to test a method but can be difficult to follow.

    $myObject->mock('give', function ($methodName, $arguments) use (&$m, &$a) {
        $m = $methodName; // 'give'
        $a = $arguments; // array(1, 2, 3)
        return 'result';
    });

    $myObject->give(1, 2, 3); // 'result'

Performing assertions within the mock callback is not recommended. If your code
fails to call the method, no assertions will be run and the test may pass.

If you are asserting within the mock, you may want to use a spy. Alternatively,
using variables by reference will allow you to perform your assertions outside
the closure.


### Interceptors

Intercepting is an improved form of mocking available to partials, providing the
instance of the partial subject to the callback.

    $myObject->intercept('foo', function ($methodName, $arguments, $instance) use (&$m, &$a) {
        $m = $methodName; // 'foo'
        $a = $arguments; // array(1, 2, 3)
        return $instances->foo($a);
    });

    $myObject->give(1, 2, 3); // 'result'


### Expectations

When you mock or stub a method it becomes expected. By default, calls to methods
that are not expected have no repercussions.

    $myObject->unknown(); // null

    $myObject->setUnexpectedMethodCallback(function ($methodName, $arguments) {
        throw new Exception;
    });

    $myObject->unknown(); // throws Exception


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
