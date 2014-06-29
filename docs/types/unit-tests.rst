Writing XUnit-Style Tests
=========================

`XUnit <http://en.wikipedia.org/wiki/XUnit>`_-style tests are methods inside a
class.

Convention over Configuration & Inheritance
-------------------------------------------

Rather than forcing you to extend a ``TestCase`` base class demeanor favors a
naming convention for test classes: they must end with the suffix ``Test``.

.. code-block:: php

    <?php

    // will be treated as a container for test cases
    class SomeTest
    {
        // ...
    }

    // demeanor will ignore this class
    class Something
    {
        // ...
    }

Additionally all test methods must start with ``test`` and be public.

.. code-block:: php

    <?php

    class SomeTest
    {
        // will be turned into a test case
        public function testSomeObjectDoesStuff()
        {
            // ...
        }

        // not a test
        public function someObjectDoesOtherStuff()
        {

        }

        // also not a test
        private function testPrivateMethodsAreIgnored()
        {

        }
    }

Using Counterpart Assertions
----------------------------

Starting with `Counterpart <http://docs.counterpartphp.org/>`_ 1.4, ``Counterpart\Assert``
and ``Counterpart\Matchers`` are traits. You can embed them in your test classes.

.. code-block:: php

    <?php

    class SomeOtherTest
    {
        use \Counterpart\Assert;
        use \Counterpart\Matchers;

        public function testSomething()
        {
            $this->assertTrue(true);
            // instead of Assert::assertTrue(true)

            $this->assertThat($this->arrayHasKey('one'), ['one' => true]);
            // instead of Assert::assertThat(Matchers::arrayHasKey('one'), ['one' => true]);
        }
    }

Annotations
-----------

The :doc:`/annotations` documentation has a ton of information about using annotations
to modify and change the behavior of unit tests.
