<?php
use Counterpart\Assert;
use Demeanor\TestContext;
use Demeanor\DefaultTestResult;

$this->before(function (TestContext $ctx) {
    $ctx['result'] = new DefaultTestResult();
});

$this->describe('#succesful', function () {
    $this->it('should return true if no modifier methods were called', function (TestContext $ctx) {
        Assert::assertTrue($ctx['result']->successful());
    });
});

$this->describe('#failed', function () {
    $this->it('Should should return true if `fail` was called', function (TestContext $ctx) {
        $ctx['result']->fail();
        Assert::assertTrue($ctx['result']->failed());
    });

    $this->it('should return false if `fail` was not called', function (TestContext $ctx) {
        Assert::assertFalse($ctx['result']->failed());
    });
});
