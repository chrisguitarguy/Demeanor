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

$this->describe('#Mockery', function () {
    $this->it('should exit with failure when mock expections are not met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/mockery_unmet_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
        Assert::assertStringContains('exactly 1 times but called 0 times', $proc->getOutput());
        Assert::assertGreaterThan(0, $proc->getExitCode());
    });

    $this->it('should exit successfully when mock expections are met', function (TestContext $ctx) {
        $proc = new Process(DEMEANOR_BINARY, __DIR__.'/Fixtures/mockery_met_test');
        $proc->run();

        $ctx->log(sprintf('STDOUT> %s', $proc->getOutput()));
        $ctx->log(sprintf('STDERR> %s', $proc->getErrorOutput()));
        Assert::assertEquals(0, $proc->getExitCode());
    });
});
