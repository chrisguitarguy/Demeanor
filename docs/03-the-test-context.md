# The Test Context

Demeanor has a concept of a test context that's defined by the
`Demeanor\TestContext` interface. This context object is **always** the first
argument to any test method or callback.

The test context simply lets you pass messages back to the test case -- telling
it to fail, skip, expect and exception, or log a message. Additionally the
context implements `ArrayAccess` so you can use it to store values for the
duration of the test run.

Here's an example for a unit test.

    <?php
    // SomeTest.php

    use Demeanor\TestContext;

    class SomeTest
    {
        public function testStuff(TestContext $ctx)
        {
            $ctx->skip('some reason why the test is to be skipped');
        }

        public function testThisWillBeFailed(TestContext $ctx)
        {
            $ctx->fail('The reason for the failure here');
        }

        public function testLogs(TestContext $ctx)
        {
            $ctx->log('some message here');
        }

        public function testExpectException(TestContext $ctx)
        {
            $ctx->expectException('Exception');
            throw new \Exception('this does not cause the test to fail');
        }
    }

Of these `log` is probably the more interesting. These message are not shown to
the user unless they've cranked up the verbosity on the command line test runner
(with the `-v|-vv|-vvv` options) or the test fails.

The same test context object that's passed to the actually test method/callback
is also passed to the before and after callbacks. This is very useful for
spec-style tests.

    <?php
    // SomeClass.spec.php

    use Demeanor\TestContext;

    $this->before(function (TestContext $ctx) {
        $ctx['something'] = createSomeObjectHere();
    });

    $this->after(function (TestContext $ctx) {
        $ctx['something']->cleanup();
    });

    $this->it('should do somethin'g, function (TestContext $ctx) {
        // use $ctx['something'] here
    });

Or using annotations with unit tests (more on annotations later).

    <?php
    // AnotherTest.php

    use Demeanor\TestContext;

    /**
     * @Before(method="setUp")
     * @After(method="tearDown")
     */
    class AnotherTest
    {
        public function setUp(TestContext $ctx)
        {
            $ctx['something'] = createSomeObjectHere();
        }

        public function tearDown(TestContext $ctx)
        {
            $ctx['something']->tearDown();
        }

        public function testSomething(TestContext $ctx)
        {
            // use $ctx['something']
        }
    }
