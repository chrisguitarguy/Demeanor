Requirements
============

Requirements conditions that have to be met for a test to run. Things like PHP
version, a required extension, or a specific OS.

Setting Requirements via the Test Context
-----------------------------------------

The ``TestContext`` object for a test case will have the key ``requirements`` that
can be used used to add new requirements to a test. This should be done in a
before callback.

Unit Test Example
^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // SomeTest.php

    use Demeanor\TestContext;
    use Demeanor\Extension\Requirement\VersionRequirement;
    use Demeanor\Extension\Requirement\RegexRequirement;
    use Demeanor\Extension\Requirement\ExtensionRequirement;

    class SomeTest
    {
        public function beforeTest(TestContext $ctx)
        {
            // require PHP 5.4
            $ctx['requirements']->add(new VersionRequirement('5.4'));

            // requires a specific verison of some other software
            $ctx['requirements']->add(new VersionRequirement('1.0', getTheVersion(), 'Software Name'));

            // require a specific OS
            $ctx['requirements']->add(new RegexRequirement('/darwin/u', PHP_OS, 'operating system'));

            // require an extension
            $ctx['requirements']->add(new ExtensionRequirement('apc'));
        }

        /**
         * @Before(method="beforeTest")
         */
        public function testSomeStuff()
        {
            // requirements are checked before this is run
        }
    }

Unit test requirements can also be set with an annotation. See the
:doc:`annotations` documentation for examples.

Spec Test Example
^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
    // Some.spec.php

    use Demeanor\TestContext;
    use Demeanor\Extension\Requirement\VersionRequirement;
    use Demeanor\Extension\Requirement\RegexRequirement;
    use Demeanor\Extension\Requirement\ExtensionRequirement;

    $this->before(function (TestContext $ctx) {
        // require PHP 5.4
        $ctx['requirements']->add(new VersionRequirement('5.4'));

        // requires a specific verison of some other software
        $ctx['requirements']->add(new VersionRequirement('1.0', getTheVersion(), 'Software Name'));

        // require a specific OS
        $ctx['requirements']->add(new RegexRequirement('/darwin/u', PHP_OS, 'operating system'));

        // require an extension
        $ctx['requirements']->add(new ExtensionRequirement('apc'));
    });

    $this->it('should do something', function (TestContext $ctx) {
        // requirements are checked before this is run
    });

Setting Requirements via Annotations
------------------------------------

Unit test requirements can be set with an annotation. These are limited to PHP
version, OS, and extension requirements.

.. code-block:: php

    <?php
    // SomeOtherTest.php

    use Demeanor\TestContext;

    class SomeOtherTest
    {
        /**
         * @Require(php="5.4", os="/darwin/u", extension="apc")
         */
        public function testSomeStuff()
        {
            // requirements are checked before this is run
        }

        /**
         * Or each Require annotation can be separate
         *
         * @Require(php="5.4")
         * @Require(os="/darwin/u")
         * @Require(extension="apc")
         */
        public function testSomeOtherStuff()
        {
            // requirements are checked before this is run
        }
    }
