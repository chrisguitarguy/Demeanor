--TEST--
This is a failing test
--FILE--
<?php
echo 'here 123', PHP_EOL;
if (false === getenv('FROM_PHPT_ENV')) {
    echo "environment didn't work", PHP_EOL;
}
--EXPECTF--
here %d
--ENV--
FROM_PHPT_ENV=1
--CLEAN--
<?php echo 'the clean section', PHP_EOL;
