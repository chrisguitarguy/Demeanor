Annotations
===========

Demeanor uses a `simple annotation library <https://github.com/chrisguitarguy/Annotation>`_
to make it a bit easier to configure unit and spec tests.

Annotations are case sensitive.

Where to Put Annotations
------------------------

Annotations can be defined on the test class or test method. Annotations on the
class will be applied to all test methods in the class. A great use case for
this is running a method before every test.

.. code-block:: php

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

Additionally, annotations can be used with :doc:`specification tests <types/spec-tests>`.
The annotation *must* be in a docblock directly before a call to ``it``.

.. code-block:: php

    <?php

    /**
     * @Excpect("LogicException")
     */
    $this->it('will throw a logic exception', function () {
        throw new \LogicException();
    });

Adding Before/After Callbacks
-----------------------------

*Only supported in :doc:`unit test cases <types/unit-tests>`*.

The ``Before`` and ``After`` annotations provide ways to call methods on the test
class or some function before and after each test case.

.. code-block:: php

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
         * These two are the same:
         * @Before(method="setUp")
         * @Before("setUp")
         *
         * @Before(function="run_before_example")
         */
        public function testStuff()
        {
            // the `setUp` method is run before the `testStuff` method
            // the `run_before_example` function is also run before the
            // `testStuff method
        }
    }

Both ``Before`` and ``After`` can take a ``method`` OR ``function`` argument. As you
might expect, ``method`` calls a method on the test class before the test run and
``function`` calls a function.

Nothing will be added if the ``method`` doesn't exist or isn't public or if the
function doesn't exist.

Expecting Exceptions
--------------------

The ``Expect`` annotation can be used instead of calling ``TestContext::expectException``
in the test method. ``Expect`` requires the ``exception`` argument to work.

.. code-block:: php

    <?php
    // ExpectTest.php

    use Demeanor\TestContext;

    class ExpectTest
    {
        /**
         * These two are the same:
         * @Expect("InvalidArgumentException")
         * @Expect(exception="InvalidArgumentException")
         */
        public function testDoingSomethingThrowsException(TestContext $ctx)
        {
            // same as calling $ctx->expectException('InvalidArgumentException');
        }
    }

If the class name in the ``exception`` argument doesn't exist, the test will be
errored and will show an error message saying that the exception class wasn't
found.

Specifying Requirements
-----------------------

See the :doc:`requirements` documentation for information about using annotations
to specify requirements.

Data Providers
--------------

:doc:`Data providers <data-providers>` can also be specified with annotations.
Details on them can be found on the :doc:`data providers <data-providers>` page.

Groups
------

:doc:`groups` must be specified on unit tests using annotations. See the
:doc:`group <groups>` documentation for more information.
