--TEST--
This is a passing test
--INI--
display_errors=Off
error_reporting=Off
--FILE--
<?php
var_dump(ini_get('display_errors'));
var_dump(ini_get('error_reporting'));
--EXPECTF--
string(%d) ""
string(%d) ""
