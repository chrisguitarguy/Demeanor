Test Groups
===========

Groups provide a way to mark test with something like a tag. Some use cases for
this:

#. You might have a regression test suite and mark each test with an issue number.
#. Components that use external libraries might have their tests tagged with the
   library name.
#. Some tests are slow, so you might tag them with *slow*.

In general groups make it easy to run only certain tests from the
:ref:`CLI <groups-cli-config>`. Demeanor doesn't support group definition in the
configuration file(s), only in test code itself.

Unit Test Groups
----------------

Unit tests can be grouped with the ``@Group`` annotation.

.. code-block:: php

    <?php

    /**
     * Mark all test methods in this class with the `slow` group:
     * @Group("slow")
     */
    class SomeTest
    {
        /**
         * Multiple groups can be added at once:
         * @Group("oneGroup", 'anotherGroup')
         *
         * This is the same as:
         * @Group('oneGroup')
         * @Group('anotherGroup')
         */
        public function testSomething()
        {

        }
    }

Spec Test Groups
----------------

Spec tests can be grouped by call ``$this->group()`` inside a spec test file. Just
like before and after callbacks, child tests inherit their parent's groups.

.. code-block:: php

    <?php
    // some_test.spec.php

    $this->group('aGroup');

    $this->it('should have a group', function () {
        // this test will be in `aGroup`
    });

    $this->describe('something else', function () {
        $this->group('anotherGroup');

        $this->it('should have two groups', function () {
            // this test will be in `aGroup` and `anotherGroup`
        });
    });

The ``@Group`` annotation may also be used, but does not cause the inheritance
that ``$this->group()`` does.

.. code-block:: php

    <?php
    // some.spec.php

    /**
     * @Group("AGroup")
     */
    $this->it('has a group', function () {

    });
