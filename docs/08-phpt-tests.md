# Writing PHPT Tests

`phpt` is a special file layout that [the php core](https://qa.php.net/write-test.php)
uses for its tests.

## PHPT Basics

A `.phpt` file is separated into sections by headers that look like
`--([A-Z]+)--` where `([A-Z]+)` is replaced with some sequents of one or more
uppercase characters.

A bare minimum of the follow sections are required.

- `--TEST--`: Describes the test
- `--FILE--`: The actual php code to run (including the open `<?php` tag)
- `--EXPECT--` or `--EXPECTF--`: Something to match the output of the `--FILE--`
  section againts.

Here is a valid `.php` file

    --TEST--
    This describe what the file is meant to test: outputing hello world.
    --FILE--
    <?php
    // You need the open php tag here
    echo 'Hello, World';
    --EXPECT--
    Hello, World

This is a test that would pass. Why? Because Demeanor will execute the code in
the `--FILE--` section in a separate PHP process and compare it with the
`--EXPECT--` section. If they match: test passes.

`--EXPECTF--` can also be used to match output. This is a details of
`--EXPECTF--`'s format can be found [here](http://qa.php.net/phpt_details.php),
but here's quick overview.

- %e: A directory separator (DIRECTORY_SEPARATOR)
- %s: One or more of anything (character, whitespace, etc) except the end of
  line character. [^\r\n]+
- %S: Zero or more of anything (character, whatespace, etc) except the end of
  line character. [^\r\n]*
- %a: One or more of anything (character, whitespace, etc) including the end of
  line character. .+
- %A: Zero or more of anything, including the end of line character. .*
- %w: Zero or more whitespace characters. \s*
- %i: A signed integer value (+123, -123). [+-]?\d+
- %d: An unsigned integer value. \d+
- %x: One or more hexadecimal character. [0-9a-fA-F]+
- %f: A floating point number. [+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?
- %c: A single character. .
- %unicode|string% or %string|unicode%: Matches 'string'
- %binary_string_optional% and %unicode_string_optional%: Matches 'string'
- %u|b% or %b|u%: replaced with nothing

We could rewrite the example above to use `--EXPECTF--` so it doesn't care
whether it sees "World" or anything else

    --TEST--
    This describe what the file is meant to test: outputing hello world.
    --FILE--
    <?php
    // You need the open php tag here
    echo 'Hello, World';
    --EXPECT--
    Hello, %s

## Skipping PHPT Tests

Use the `--SKIPIF--` section. This is a bit of code that will be pased to a
separate PHP process. If the output from it start with *skip* the test will be
skipped.

    --TEST--
    Only Runs on php less than 5.4
    --SKIPIF--
    <?php if (version_compare(phpversion(), '5.4', '<')) {
        echo 'skip on php less than 5.4';
    }
    --FILE--
    <?php
    // test code here
    --EXPECT--
    some sort of output

## Cleaning Up

Use the `--CLEAN--` section to clean up after yourself. Please note that the
`--CLEAN--` section is **not** passed to the same PHP process as the `--FILE--`,
so you can expect it to have the same variables available

## Sharing Environment

If a file has the optional `--ENV--` section, it will parsed into an associative
array and passed to all PHP processes as environment variables.

    --TEST--
    Test with environment
    --ENV--
    FROM_PHPT_ENV=1
    --FILE--
    <?php
    var_dump(getenv('FROM_PHT_ENV'));
    --EXPECTF--
    %string|unicode%(%d) "1"

The `FROM_PHPT_ENV` will be available (via `getenv` or `$_ENV`, depending on
your php settings) in `--SKIPIF--`, `--FILE--`, and `--CLEAN--`.

## Does Demeanor Support *All* PHPT Features?

Definitely not. The PHP core's `run-tests.php` is still much, much more
complete. Demeanor just barely does an impression of the phpt functionality
found there.
