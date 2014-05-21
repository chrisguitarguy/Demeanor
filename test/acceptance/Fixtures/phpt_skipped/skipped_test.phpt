--TEST--
This is a failing test
--SKIPIF--
<?php echo 'skip this test', PHP_EOL;
--FILE--
<?php
echo 'here 123', PHP_EOL;
--EXPECTF--
here %d
