# Demeanor

[![Build Status](https://travis-ci.org/chrisguitarguy/Demeanor.svg?branch=master)](https://travis-ci.org/chrisguitarguy/Demeanor)

Demeanor is a multi-paradigm test harness for PHP 5.4+ that  allows you to write
Spec, [XUnit](http://en.wikipedia.org/wiki/XUnit)-style, and PHPT tests.

See the [documentation](http://docs.demeanorphp.org/en/latest/#getting-started)
for a getting started guide.

## Spec Test Example

```php
<?php
// SomeFeature.spec.php

use Counterpart\Assert;
use Demeanor\TestContext;

$this->it('should throw an exception', function (TestContext $ctx) {
    $ctx->expectException('Exception');
    throw new \Exception();
});

$this->describe('#ASubFeature', function () {
    $this->it('should always be true', function () {
        Assert::assertTrue(true);
    });
});
```

## XUnit-Style Test Example

```php
<?php
// SomeTest.php

use Counterpart\Assert;

class SomeTest
{
    public function testSomething()
    {
        Assert::assertTrue(true);
    }
}
```

### PHPT Test Example

```
--TEST---
This is a test description
--INI--
display_error=On
error_reporting=On
--SKIPIF--
<?php
if (version_compare('5.4', phpversion(), '<')) {
    echo 'skip: this test requires PHP 5.4+';
}
?>
--FILE--
<?php
echo 'this is the test code', PHP_ECOL
echo 'here ', time(), PHP_EOL;
?>
--EXPECTF--
this is the test code
here %d
```

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
