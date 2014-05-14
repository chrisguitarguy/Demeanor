# Getting Started

Demeanor can be installed with [composer](https://getcomposer.org/), please read
the composer [getting started](https://getcomposer.org/doc/00-intro.md) page to
learn how to get everything set up.

Once that's done, add `demeanor/demeanor` to your `require-dev` dependencies in
`composer.json`

    {
        "require-dev": {
            "demeanor/demeanor": "dev-master"
        }
    }

Then run `composer install` or `composer update` with the `--dev` flag.

## Configuration

Other documentation explains the `demeanor.json` configuration more fully, but,
for now, we're going to set up two test suites.

    {
        "testsuites": {
            "unit": {
                "type": "unit",
                "directories": [
                    "test/unit"
                ]
            },
            "spec": {
                "type": "spec",
                "directories": [
                    "test/spec"
                ]
            }
        }
    }

The `testsuites` argument is required and *must* be an object. If it's not the
test runner will complain.

The keys of the `testsuites` object are the suite names and the values are their
configuration. `type` tells Demeanor what type of test suite its dealing with.
Valid values are `unit`, `spec`, or (in the future) `story`. `directories` tells
the test run where to look for the test files. How Demeanor finds those files
varies by suite type.

## Assertions

Demeanor uses a library called [Counterpart](https://github.com/chrisguitarguy/Counterpart)
to deal with assertions. You'll use the `Counterpart\Assert` class and call one
of it's `assert*` methods. The last argument of all `assert*` methods is a
message that can be used to describe the business case or importances of the
assertion.

Here are some examples:

    <?php
    use Counterpart\Assert;

    Assert::assertTrue(true, 'True is somehow false, things are very broken');
    Assert::assertFalse(false);
    Assert::assertNull(null);
    Assert::assertType('string', 'this is a string');

## Your First Unit Test

Unit test cases are methods inside of a class. Every time a method is run, a new
instance of it's class is created.

Test class names **must** end with `Test` and test method must start with the
word `test`. Demeanor will look for all files that end with `Test.php` in the
directories defined in the `directories` configuration above.

    <?php
    // test/unit/TruthyTest.php

    use Counterpart\Assert;

    class TruthyTest
    {
        public function testTruthyValuesReturnTrue()
        {
            Assert::assertTrue(filter_var('yes', FILTER_VALIDATE_BOOLEAN));
        }

        public function testFalsyValuesReturnFalse()
        {
            Assert::assertFalse(filter_var('no', FILTER_VALIDATE_BOOLEAN));
        }
    }

## Your First Spec Test

Spec tests use a `describe` and `it` API to to define a
specification for an object. A specification is just a set of expected
behaviors.

In demeanor, a spec test looks like this.

    <?php
    // filter_var.spec.php

    use Counterpart\Assert;

    /** @var Demeanor\Spec\Specification $this */
    $this->describe('#truthyValues', function () {
        $this->it('should return true when given "yes"', function () {
            Assert::assertTrue(filter_var('yes', FILTER_VALIDATE_BOOLEAN));
        });
        $this->it('should return true when given a "1"', function () {
            Assert::assertTrue(filter_var(1, FILTER_VALIDATE_BOOLEAN));
        });
    });

    $this->describe('#falsyValues', function () {
        $this->it('should return false when given "no"', function () {
            Assert::assertFalse(filter_var('no', FILTER_VALIDATE_BOOLEAN));
        });
        $this->it('should return false when given "0"', function () {
            Assert::assertFalse(filter_var(0, FILTER_VALIDATE_BOOLEAN));
        });
    });

Each call to `it` creates a new test case. When the `directories` argument is
used for a `spec` test suite, all files that end with `.spec.php` are located
and compiled to test cases.

## Running the Tests

A binary will be [installed via composer](https://getcomposer.org/doc/articles/vendor-binaries.md)
in your `bin-dir` (`vendor/bin` by default). Once a configuration file and some
tests are set up, use the command line to run `php vendor/bin/demeanor` or
`./vendor/bin/demeanor` to run the tests.

## Need More Examples?

Demeanor uses itself to test -- well, to test itself. So look in the `test`
directory of this repo for a bunch more examples.
