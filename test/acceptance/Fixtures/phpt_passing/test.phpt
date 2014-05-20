--TEST--
This is a failing test
--FILE--
<?php
echo 'here 123', PHP_EOL;
--EXPECTF--
here %d
