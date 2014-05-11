# Demeanor

Demeanor is a multi-paradigm testing framework for PHP.

Or rather, it will be, it's still in progress.

*Multi-paradigm* means that it will, eventually, let you write standard unit
tests along with Spec and Story BDD tests.

## Configuration

In the root directory of your project create a `demeanor.json` file.

    {
        "testsuites": {
            "unittests": {
                "type": "unit"
                , "bootstrap": [
                    "test/bootstrap.php"
                ]
                , "directories": [
                    "test/unit"
                ]
                , "files": [
                    "test/path/to/a_file.php"
                ]
                , "glob": [
                    "test/unit/*Test.php"
                ]
            }
        }
    }

There's only a single key, `testsuites`, which will contain an object that
defines all your test suites.

#### Test Suite Configuration

- `type` is the type of of test suite. `unit`, `spec`, or `story` are all
  acceptable
- `bootstrap` are files that should be included before the test suite runs, this
  is dont with `require_once` internally
- `directories` is a list of directories in which the test files reside. How
  files are located in this directories depends on the type of suite.
- `files` a list of files taht contain test cases. These are simply checked for
  existence and then loaded
- `glob`, as you might expect, is a list of glob patterns that can be used to
  locate files. This uses PHP's `glob` function behind the scenes.

`directories`, `files`, or `glob` may all be used on or only one or two may be
used, it's up to you.

## Unit Tests

Unit tests are methods inside classes. There are some rules for these:

1. Test classes must end with the word `Test`
2. Test method must start with the word `test`

Here's an example:

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

#### Locating Test Classes

If `directories` is specified in a unit test suite configuration, it will look
for files that end in `Test.php`.

    test/unit/
        TestCase.php <- doesn't get loaded
        TruthyTest.php <- DOES get loaded

#### Set up and Tear Down

A new object is created for every test method, so you can do your setup and tear
down in `__construct` and `__destruct`.

Take the test case above, which has two test methods. Internally Demeanor's
process works like this:

1. Create a new instance of `TruthyTest`
2. Run `testTruthyValuesReturnTrue`
3. Create a new instance of `TruthyTest`
4. Run `testFalsyValuesReturnFalse`

## License (Apache)

Copyright 2014 Christopher Davis

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
