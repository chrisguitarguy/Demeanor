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

$this->group('phpt');

$this->after(function (TestContext $ctx) {
    $ctx->log(sprintf('STDOUT> %s', $ctx['proc']->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $ctx['proc']->getErrorOutput()));
});

$this->it('should exit with a failure code when a phpt test fails', function (TestContext $ctx) {
    $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/phpt_failing');
    $ctx['proc']->run();

    Assert::assertGreaterThan(0, $ctx['proc']->getExitCode());
});

$this->it('should exit with a success code when all phpt tests pass', function (TestContext $ctx) {
    $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/phpt_passing');
    $ctx['proc']->run();

    Assert::assertEquals(0, $ctx['proc']->getExitCode());
});

$this->it('should exit with a success code when test are skipped and others pase', function (TestContext $ctx) {
    $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/phpt_skipped');
    $ctx['proc']->run();

    Assert::assertEquals(0, $ctx['proc']->getExitCode());
    Assert::assertStringContains('Skipped: 1', $ctx['proc']->getOutput());
});

$this->it('should should set ini values from the INI file section', function (TestContext $ctx) {
    $ctx['proc'] = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/phpt_withini');
    $ctx['proc']->run();

    Assert::assertEquals(0, $ctx['proc']->getExitCode());
});
