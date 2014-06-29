Writing Spec Tests
==================

Demeanor's spec test API is heavily inspired by tools like `RSpec <http://rspec.info/>`_
and `Jasmine <http://jasmine.github.io/>`_.

Spec Test Basics
----------------

A spec test is a file that is ``included`` inside an instance of ``Demeanor\Spec\Specification``.
Demeanor, by default, looks for files that end in ``.spec.php``, the part of the
file before that suffix is used as the initial test name.

.. code-block:: php

    <?php
    // SomeTest.spec.php

    // by default Demeanor does this to set up the initial test name/context
    // It's simply the file name minus the `.spec.php`
    $this->describe('SomeTest');

    // of course, if you don't like that, it can be changed.
    $this->describe('Some Cool Feature');

    // generally spec files contain one or more calls to `it`. Whenever a call
    // to `it` happens, a test case is created. The first argument of `it` is
    // used as part of the name of the test
    $this->it('should be true', function () {
        Assert::assertTrue(true, 'true is not true, something is horribly wrong');
    });

    $this->it('should be false', function () {
        Assert::assertFalse(false, 'false is not false, something is horribly wrong');
    });

Grouping Tests with Describe
----------------------------

As explained above, when demeanor includes a file, it calls ``describe`` with part
of the file name. This create a group of tests. This is really nothing more than
prefixing the name of the test. The example above would generate two test cases
with the names:

- [Some Cool Feature] should be true
- [Some Cool Feature] should be false

Demeanor does this for you automatically, but sometimes futher grouping is required.
For instance, a given feature or behavior might have several sub-features or behaviors
that should be grouped. This can be done by nesting calls to ``describe``.

.. code-block:: php

    <?php
    // SomeOtherTest.spec.php

    use Counterpart\Assert;
    use Demeanor\TestContext;

    // When describe is given a second argument, a closure, it creates an entirely
    // new instance of `Demeanor\Spec\Specification` to use
    $this->describe('#SomeSubFeature', function () {
        $this->it('will throw an exception', function (TestContext $ctx) {
            $ctx->expectException('Exception');
            throw new \Exception('broken');
        });

        $this->it('will equal zero', function () {
            Assert::assertEquals(0, 0);
        });
    });

This will generate two test cases with the following names:

#. [SomeOtherTest#SomeSubFeature] will throw an exception
#. [SomeOtherTest#SomeSubFeature] will equal zero

Describe can be nested as many times as you wish.

Before & After Callbacks
------------------------

Sometimes you need to set up or tear down state before and after a test. This can
done by calling ``before`` and ``after`` in your spec file.

.. code-block:: php

    <?php
    // BeforeAfter.spec.php

    use Counterpart\Assert;
    use Demeanor\TestContext;

    $this->before(function (TestContext $ctx) {
        // use the test context to pass values
        $ctx['one'] = true;
    });

    $this->after(function (TestContext $ctx) {
        unset($ctx['one']);
    });

    $this->it('should have values set for before and after', function (TestContext $ctx) {
        Assert::assertTrue($ctx['one']);
    });

Unfortunately ``before`` and ``after`` come with some limitations. They are dependent
on their position within the file related to the test cases. For instance: if a call
to ``after`` is later in the file than a call to ``it`` that after callback will
not be run on earlier test cases.

.. code-block:: php

    <?php
    // BeforeAfter.spec.php

    use Demeanor\TestContext;

    $this->before(function (TestContext $ctx) {
        // ...
    });

    $this->it('should be a test', function (TestContext $ctx) {
        // test code here
    });

    $this->after(function (TestContext $ctx) {
        // this will never be run
    });

Test Groups/Tags
----------------

Apart from grouping tests with ``describe``, test groups are ways to *tag* test
so they can be easily run or excluded later. See :doc:`/groups` for more information.

Describe with Before, After, and Group
--------------------------------------

When ``describe`` is called inside a spec file, the new ``Demeanor\Spec\Specification``
object that's created for it will inherit all of the before and after callbacks as
well as any groups from the outer scope.

.. code-block:: php

    <?php
    // BeforeAfterDescribe.spec.php

    use Counterpart\Assert;
    use Demeanor\TestContext;

    $this->group('aGroup');

    $this->before(function (TestContext $ctx) {
        // this will run before EVERY test case in this file, even the
        // ones inside another `describe` call
        $ctx['one'] = true;
    });

    // this test will be placed in `aGroup`
    $this->it('has a before callback and group', function (TestContext $ctx) {
        Assert::assertTrue($ctx['one']);
    });

    $this->describe('#NestedDescribe', function () {
        // this test will also be placed in `aGroup`
        $this->it('also has a before callback and group', function (TestContext $ctx) {
            Assert::assertTrue($ctx['one']);
        });
    });

The relationship doesn't work the other way, however. Before and after callbacks
inside a ``describe`` are jailed there.

.. code-block:: php

    <?php
    // BeforeAfterDescribe.spec.php

    use Counterpart\Assert;
    use Demeanor\TestContext;

    $this->describe('#NestedDescribe', function () {
        $this->group('inner group');

        $this->before(function (TestContext $ctx) {
            $ctx['one'] = true;
        });

        $this->it('has a before callback and group', function (TestContext $ctx) {
            Assert::assertTrue($ctx['one']);
        });
    });

    $this->it(
        'does not share a the same before and group with the inner spec',
        function (TestContext $ctx) {
            Assert::assertArrayDoesNotHaveKey('one', $ctx);
        }
    );

