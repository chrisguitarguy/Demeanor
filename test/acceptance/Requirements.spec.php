<?php
/**
 * Copyright 2014 Christopher Davis <http://christopherdavis.me>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Demeanor
 * @copyright   2014 Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/apache-2.0 Apache-2.0
 */

use Symfony\Component\Process\Process;
use Counterpart\Matchers;
use Counterpart\Assert;
use Demeanor\TestContext;

$this->describe('#InBefore', function () {
    $this->it('should mark test as skipped when requirements are not met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/req_notmet_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));

        Assert::assertStringContains('Skipped', $proc->getOutput());
    });

    $this->it('should not mark test as skipped when requirement are met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/req_met_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));

        Assert::assertThat(Matchers::stringDoesNotContain(': Skipped'), $proc->getOutput());
    });
});

$this->describe('#Annotation', function () {
    $this->it('should mark test as skipped when requirements as not met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/req_annotnotmet_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));

        Assert::assertStringContains('Skipped', $proc->getOutput());
    });

    $this->it('should not mark test as skipped when requirement are met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/req_annotmet_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));

        Assert::assertThat(Matchers::stringDoesNotContain(': Skipped'), $proc->getOutput());
    });

    $this->it('should should mark test as skipped when specfication test requirements are not met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/req_annotnotmet_spectest');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));

        Assert::assertStringContains('Skipped', $proc->getOutput());
    });
});
