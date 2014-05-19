--TEST--
This test will be skipped
--SKIPIF--
<?php echo 'Skip this test will always be skipped', PHP_EOL;
--FILE--
<?php echo "We'll never get here", PHP_EOL;
--EXPECT--
doesn't matter
