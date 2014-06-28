<?php
use Demeanor\TestContext;

$this->describe('#GroupOne', function () {
    $this->group('groupOne');
    $this->it('is in group one', function (TestContext $ctx) {
        $ctx->log('hello from group one');
    });
});

$this->describe('#GroupTwo', function () {
    $this->group('groupTwo');
    $this->it('is in group two', function (TestContext $ctx) {
        $ctx->log('hello from group two');
    });
});
