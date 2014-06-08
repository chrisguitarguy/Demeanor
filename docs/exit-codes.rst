Exit Codes
==========

When all tests are successful, Demeanor will exit with the status code ``0``.

If tests fail or error, the exit code will ``1``.

If some sort of error happens (configuration issue, etc) before tests are run,
the exit code will be ``2``.

If, for some reason, the ``demeanor`` command line script can't find a composer
autoload file, it will exit with the status code ``3``.
