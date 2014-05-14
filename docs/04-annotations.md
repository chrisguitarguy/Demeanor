# Annotations

Demeanor uses a [simple annotation library](https://github.com/chrisguitarguy/Annotation)
to make it a bit easier to configure unit tests.

Annotations are case sensitive.

## Class or Method?

Annotations can be defined on the test class or test method. Annotations on the
class will be applied to all test methods in the class. A great use case for
this is running a method before every test.

    <?php
    // AnnotationTest.php

    /**
     * @Before(method="setUp")
     */
    class AnnotationTest
    {
        public function setUp()
        {

        }
    }

## Adding Before/After Callbacks

The `Before` and `After` annotations provide ways to call methods on the test
class or some function before and after each test case.

    <?php
    // BeforeTest.php

    function run_before_example()
    {

    }

    class BeforeTest
    {
        public function setUp()
        {

        }

        /**
         * @Before(method="setUp")
         * @Before(function="run_before_example")
         */
        public function testStuff()
        {
            // the `setUp` method is run before the `testStuff` method
            // the `run_before_example` function is also run before the
            // `testStuff method
        }
    }

Both `Before` and `After` can take a `method` OR `function` argument. As you
might expect, `method` calls a method on the test class before the test run and
`function` calls a function.

Nothing will be added if the `method` doesn't exist or isn't public or if the
function doesn't exist.

## Expecting Exceptions

The `Expect` annotation can be used instead of calling `TestContext::expectException`
in the test method. `Expect` requires the `exception` argument to work.

    <?php
    // ExpectTest.php

    use Demeanor\TestContext;

    class ExpectTest
    {
        /**
         * @Expect(exception="InvalidArgumentException")
         */
        public function testDoingSomethingThrowsException(TestContext $ctx)
        {
            // same as calling $ctx->expectException('InvalidArgumentException');
        }
    }

If the class name in the `exception` argument doesn't exist, the test will be
errored and will show an error message saying that the exception class wasn't
found.
