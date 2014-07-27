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

$this->after(function (TestContext $ctx) {
    $ctx->log(sprintf('STDOUT> %s', $ctx['proc']->getOutput()));
    $ctx->log(sprintf('STDERR> %s', $ctx['proc']->getErrorOutput()));
});

$this->describe('#FilterName', function () {
    $this->it('should not run filtered tests', function (TestContext $ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' --filter-name "will pass"',
            __DIR__.'/Fixtures/filter_name'
        );
        $ctx['proc']->run();

        Assert::assertEquals(0, $ctx['proc']->getExitCode());
    });
});

$this->describe('#Groups', function () {
    $this->it('should only run tests not in a given group', function (TestContext $ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' --include-group groupOne -vvv',
            __DIR__.'/Fixtures/filter_group'
        );
        $ctx['proc']->run();

        $out = $ctx['proc']->getOutput();
        Assert::assertStringContains('hello from group one', $out);
        Assert::assertStringDoesNotContain('hello from group two', $out);
    });

    $this->it('should not run groups that are excluded', function (TestContext $ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' --exclude-group groupOne -vvv',
            __DIR__.'/Fixtures/filter_group'
        );
        $ctx['proc']->run();

        $out = $ctx['proc']->getOutput();
        Assert::assertStringContains('hello from group two', $out);
        Assert::assertStringDoesNotContain('hello from group one', $out);
    });
});

$this->describe('#Paths', function () {
    $this->it('should only run tests in a given directory', function (TestContext $ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' -vvv subdir/',
            __DIR__.'/Fixtures/filter_paths'
        );

        $ctx['proc']->run();

        Assert::assertEquals(0, $ctx['proc']->getExitCode());
    });

    $this->it('should only run tests in a given file', function (TestContext $ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' -vvv willpass_test.php',
            __DIR__.'/Fixtures/filter_paths'
        );

        $ctx['proc']->run();

        Assert::assertEquals(0, $ctx['proc']->getExitCode());
    });

    $this->it('should exit with an error exception when an invalid path is given', function ($ctx) {
        $ctx['proc'] = new Process(
            DEMEANOR_BINARY.' -vvv does_not_exist.php',
            __DIR__.'/Fixtures/filter_paths'
        );

        $ctx['proc']->run();

        Assert::assertStringContains('not a valid file', $ctx['proc']->getErrorOutput());
        Assert::assertGreaterThan(0, $ctx['proc']->getExitCode());
    });
});
