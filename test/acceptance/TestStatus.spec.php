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

$this->it('should exit successfully when all tests pass', function (TestContext $ctx) {
    $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/successful_tests');
    $proc->run();

    $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
    Assert::assertEquals(0, $proc->getExitCode());
});

$this->it('should exit successfully when tests are skipped', function (TestContext $ctx) {
    $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/skipped_tests');
    $proc->run();

    $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
    Assert::assertEquals(0, $proc->getExitCode());
});

$this->it('should exit with a failure code when tests fail', function (TestContext $ctx) {
    $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/failure_tests');
    $proc->run();

    $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
    Assert::assertGreaterThan(0, $proc->getExitCode());
});

$this->it('should exit with a failure code when tests cause warnings', function (TestContext $ctx) {
    $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/warning_tests');
    $proc->run();

    $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
    Assert::assertGreaterThan(0, $proc->getExitCode());
});

$this->it('should exclude tests based on the configuration', function (TestContext $ctx) {
    $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/excluded_tests');
    $proc->run();

    $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
    Assert::assertEquals(0, $proc->getExitCode());
});
