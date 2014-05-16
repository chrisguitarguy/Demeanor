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
use Counterpart\Assert;
use Demeanor\TestContext;

$this->describe('#BeforeAfter', function () {
    $this->it('should run before and after functions and exit successfully', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY.' -vvv', __DIR__.'/Fixtures/beforeafter_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
        Assert::assertStringContains('in before callback', $proc->getOutput());
        Assert::assertStringContains('in after callback', $proc->getOutput());
        Assert::assertEquals(0, $proc->getExitCode());
    });
});

$this->describe('#Expect', function () {
    $this->it('should error the test when a non-existent exception is used and exit with failure', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/badexpect_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
        Assert::assertGreaterThan(0, $proc->getExitCode());
    });

    $this->it('should add expected exception when valid exception is used and exit successfully', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/goodexpect_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
        Assert::assertEquals(0, $proc->getExitCode());
    });
});

$this->describe('#Provider', function () {
    $this->after(function (TestContext $ctx) {
        $ctx->log(sprintf('STDOUT> %s', $ctx['proc']->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $ctx['proc']->getErrorOutput()));
        Assert::assertEquals(0, $ctx['proc']->getExitCode());
        Assert::assertStringContains('Data Set #', $ctx['proc']->getOutput());
    });

    $this->it('should set a valid provider with a static method and exit successfully', function (TestContext $ctx) {
        $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/dataprovider_method');
        $ctx['proc']->run();
    });

    $this->it('should set a valid provider with a function and exit successfully', function (TestContext $ctx) {
        $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/dataprovider_func');
        $ctx['proc']->run();
    });

    $this->it('should set a valid provider with inline data and exit successfully', function (TestContext $ctx) {
        $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/dataprovider_inline');
        $ctx['proc']->run();
    });
});
