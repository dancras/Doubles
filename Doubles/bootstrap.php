<?php

/* Class names and folder structure match the namespacing used so a simple autoloader should
 * be able to resolve all classes if you add the doubles folder to the include path.
 * 
 * Alternatively, just require_once this bootstrap. */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'IComponent.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'ISubject.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'DoublesException.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'ExceptionContainer.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'SimpleSubject.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'TestDouble.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'TestDoubleFactory.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy' . DIRECTORY_SEPARATOR . 'CallCounter.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy' . DIRECTORY_SEPARATOR . 'OneCallException.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy' . DIRECTORY_SEPARATOR . 'MethodSpy.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy' . DIRECTORY_SEPARATOR . 'ISpy.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy' . DIRECTORY_SEPARATOR . 'SpyComponent.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Spy.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'IExpecter.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'IExpectation.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'IStub.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'IMock.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'ExpectationComponent.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'StubComponent.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'MockComponent.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Mock.php';