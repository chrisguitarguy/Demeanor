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

namespace Demeanor\Phpt;

use Counterpart\Assert;
use Demeanor\Event\DefaultEmitter;

class PhptTestCaseTest
{
    private $emitter;

    public function __construct()
    {
        $this->emitter = new DefaultEmitter();
        $this->executor = \Mockery::mock('Demeanor\\Phpt\\Executor[execute]');
        $this->parser = \Mockery::mock('Demeanor\\Phpt\\Parser');
    }

    public function testPhptWithSuccessfulSkipCodeMarksTestAsSkipped()
    {
        $tc = $this->createTestCase(__DIR__.'/../Fixtures/skipped.phpt');
        $this->executorReturns('Skip here is the skip reason', '');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->skipped());
    }

    public function testPhptWithUmatchingExpectfMarksTestAsFailed()
    {
        $tc = $this->createTestCase(__DIR__.'/../Fixtures/expectf.phpt');
        $this->executorReturns('not here', '');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->failed());
    }

    public function testPhptWithUnmatchingExpectMarksTestAsFailed()
    {
        $tc = $this->createTestCase(__DIR__.'/../Fixtures/expect.phpt');
        $this->executorReturns('not here', '');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->failed());
    }

    public function testPhptWithCleanCallsExecutorMoreThanOnce()
    {
        $tc = $this->createTestCase(__DIR__.'/../Fixtures/clean.phpt');
        $this->executor->shouldReceive('execute')
            ->atLeast(2)
            ->andReturn(['here', '']);

        $result = $this->runTest($tc);

        Assert::assertTrue($result->successful());
    }

    private function runTest(PhptTestCase $tc)
    {
        return $tc->run($this->emitter);
    }

    private function executorReturns($stdin, $stdout, $index=0)
    {
        $this->executor->shouldReceive('execute')
            ->atLeast(1)
            ->andReturn([$stdin, $stdout]);
    }

    private function createTestCase($filename)
    {
        return new PhptTestCase($filename, $this->executor);
    }
}
