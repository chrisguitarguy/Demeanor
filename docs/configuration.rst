Configuration
=============

Demeanor use `JSON <http://www.json.org/>`_ for configuration and looks for the
configuration files ``demeanor.json`` and ``demeanor.dist.json`` by default. A
custom configuration file can be used by using the ``-c`` or ``--config`` command
line options.

.. code-block:: bash

    ./vendor/bin/demeanor --config a_custom_config.json

.. _test-suites:

Test Suites
-----------

The centerpiece of Demeanor's configuration is the ``testsuites`` argument. If
no suites are defined, the command line test runner will error. Similarly if
``testsuites`` isn't a JSON object, the runner will error.

Each test suite can have it's own bootstrap file(s) as well as define it's own
test locations.

Here's a complete example:

.. code-block:: json

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
                ],
                "exclude": {
                    "directories": [
                        "test/classes/not_this_one"
                    ]
                    , "files": [
                        "test/path/to/exclude.php"
                    ]
                    , "glob": [
                        "test/exclude/*.php"
                    ]
                }
            }
        }
    }

* ``type`` is the type of test suite. This just tells demeanor what to do with the
  suite itself. Valid values are ``unit``, ``spec``, and (eventually) ``story``.
* ``bootstrap`` is a list of files that will be ``require_once``'d before of the
  suites tests are run. Use these files to do any setup for the test suite.
* ``directories`` tells demeanor to look for files in a directory. What files it
  looks for depends on the suite type.

    * ``unit`` test suites look for files that end in ``Test.php``
    * ``spec`` test suites look for files that end in ``spec.php``

* ``files`` is a list of files that will be treated as if they contain test cases.
* ``glob`` is a list of `glob <http://www.php.net/manual/en/function.glob.php>`_
  patterns that will be used to locate test files.
* ``exclude`` is used to blacklist files from your test suite. It's an object that
  looks very similar to the test suite itself. ``directories``, ``files``, and
  ``glob`` work exactly as they do in the test suite itself.

Default Test Suites
-------------------

There's a good chance you won't want to run all your test suites all the time.
For instance, acceptance tests often take a long time -- they'll test your
complete system end to end.

That's where the ``default-suites`` configuration option comes in. When defined
only the test suites defined in it's array (or string) will be run with the
naked ``demeanor`` command.

``default-suites`` may be an array.

.. code-block:: json

    {
        "default-suites": ["a_suite"],
        "testsuites": {
            "a_suite": {
                "type": "spec",
                "directories": [
                    "test/spec"
                ]
            }
        }
    }

Or it can just be a string.

.. code-block:: json

    {
        "default-suites": "a_suite",
        "testsuites": {
            "a_suite": {
                "type": "spec",
                "directories": [
                    "test/spec"
                ]
            }
        }
    }

If a suite that doesn't exist is supplied, the ``demeanor`` CLI will fail.

.. code-block:: json

    {
        "default-suites": "this-will-not-work",
        "testsuites": {
            "a_suite": {
                "type": "spec",
                "directories": [
                    "test/spec"
                ]
            }
        }
    }

How can I run other test suites then?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use the ``--testuite`` (or ``-s``) command line option.

.. code-block:: bash

    ./vendor/bin/demeanor --testsuite a_suite

Or use a few of them.

.. code-block:: bash

    ./vendor/bin/demeanor -s a_suite -s another_suite

Or use the ``--all`` (or ``-a``) option to run all test suites.

.. code-block:: bash

    ./vendor/bin/demeanor --all

Subscribers
-----------

``subscribers`` can be defined in ``demeanor.json`` to add event subscribers to that
hook in and change how the test runs work.

``subscribers`` should be a list of class names that implement
``Demeanor\Event\Subscriber``.

.. code-block:: json

    {
        "subscribers": [
            "Acme\\Example\\TestSubscriber"
        ],
        "testsuites": {
            ...
        }
    }

These subscribers should have a argumentless constructor. Demeanor uses the
event subscriber API itself, look in the ``src/Subscriber`` directory of the
`demeanor repo <https://github.com/chrisguitarguy/Demeanor>`_ for examples.

.. _code-coverage-config:

Code Coverage
-------------

Demeanor's code coverage uses a whitelist of files to generate reports. Unless
``coverage`` is defined in ``demeanor.json`` no coverage will be reported on.

``coverage`` looks very close to a test suite.

.. code-block:: json

    {
        "coverage": {
            "reports": {
                "text": "coverage/coverage.txt",
                "html": "coverage/html_dir",
                "diff": "coverage/diff_dir"
            },
            "directories": [
                "src/"
            ],
            "files": [
                "path/to/a/file.php"
            ],
            "glob": [
                "files/*.php"
            ],
            "exclude": {
                "directories": [
                    "src/NotThisOne"
                ],
                "files": [
                    "path/to/a/file/excluded.php"
                ],
                "glob": [
                    "files/nothere/*.php"
                ]
            }
        }
    }

- ``directories`` is a list of directories that will be search for all files
  ending with ``.php``
- ``files`` and ``glob`` work as described in the :ref:`test suites <test-suites>`
- ``exclude`` can be used to leave files out of the coverage report. All of its
  keys (``directories``, ``files``, and ``glob``) work the same as described in
  the two points above.
- ``reports`` is really the only coverage specific part of the configuration. It
  defines a set of coverage reports with the report type as the key and an output
  path as the value. Reports is optional, report options can be specified from
  the CLI.

If no directories, files, or glob keys are provided, Demeanor will no generate
any coverage reports or may generate an empty ``index.html``.

Please see :doc:`code-coverage` for a more complete guide to report types.

.. _code-coverage-cli-config:

Command Line Coverage Configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

- The ``--no-coverage`` option will completely disable collection and rendering
  of coverage reports.
- ``--coverage-html`` tells Demeanor to use a the supplied directory to output a
  html report.
- ``--coverage-text`` tells Demeanor use the supplied file path to write a text
  report.
- ``--coverage-diff`` tells Demeanor to use the supplied directory to output a
  diff report.

Some examples:

.. code-block:: bash

    # disable coverage completely
    ./vendor/bin/demeanor --no-coverage

    # output an HTML report to the coverage directory
    ./vendor/bin/demeanor --coverage-html=coverage

    # output a diff reprot to the coverage directory
    ./vendor/bin/demeanor --coverage-diff=coverage

    # output a text report to the file coverage.txt
    ./vendor/bin/demeanor --coverage-text=coverage.txt
