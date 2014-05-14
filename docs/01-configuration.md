# Configuration

Demeanor use [JSON](http://www.json.org/) for configuration and looks for the
configuration files `demeanor.json` and `demeanor.dist.json` by default. A custom
configuration file can be used by using the `-c` or `--config` command line
options.

    shell$ ./vendor/bin/demeanor --config a_custom_config.json

## Test Suites

The centerpiece of Demeanor's configuration is the `testsuites` argument. If no
suites are defined, the command line test runner will error. Similarly if
`testsuites` isn't a JSON object, the runner will error.

Each test suite can have it's own bootstrap file(s) as well as define it's own
test locations.

Here's a complete example:

    {
        "testsuites": {
            "A Test Suite": {
                "type": "unit",
                "bootstrap": [
                    "test/bootstrap.php"
                ],
                "directories": [
                    "test/classes",
                    "test/another_directory"
                ],
                "files": [
                    "test/path/to/a/file.php"
                ],
                "glob": [
                    "test/files/*Test.php"
                ]
            }
        }
    }

- `type` is the type of test suite. This just tells demeanor what to do with the
  suite itself. Valid values are `unit`, `spec`, and (eventually) `story`.
- `bootstrap` is a list of files that will be `require_once`'d before of the
  suites tests are run. Use these files to do any setup for the test suite.
- `directories` tells demeanor to look for files in a directory. What files it
  looks for depends on the suite type.
    * `unit` test suites look for files that end in `Test.php`
    * `spec` test suites look for files that end in `spec.php`
- `files` is a list of files that will be treated as if they contain test cases.
- `glob` is a list of [`glob`](http://www.php.net/manual/en/function.glob.php)
  patterns that will be used to locate test files.

## Subscribers

`subscribers` can be defined in `demeanor.json` to add event subscribers to that
hook in and change how the test runs work.

`subscribers` should be a list of class names that implement
`Demeanor\Event\Subscriber`.

    {
        "subscribers": [
            "Acme\\Example\\TestSubscriber"
        ],
        "testsuites": {
            ...
        }
    }

These subscribers should have a argumentless constructor. Demeanor uses the
event subscriber API itself, look in the `src/Extension` directory for some
examples.