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

namespace Demeanor;

use Counterpart\Assert;

class DefaultResultSetTest
{
    public function testAddingFailedTestIncreasesFailedCount()
    {
        $set = $this->createSet();
        $result = $this->createResult();
        $result->fail();

        Assert::assertEquals(0, $set->failedCount());
        Assert::assertCount(0, $set);
        $set->add($this->testcase(), $result);
        Assert::assertEquals(1, $set->failedCount());
        Assert::assertCount(1, $set);
    }

    public function testAddingErroredTestIncreasesErrorCount()
    {
        $set = $this->createSet();
        $result = $this->createResult();
        $result->error();

        Assert::assertEquals(0, $set->errorCount());
        Assert::assertCount(0, $set);
        $set->add($this->testcase(), $result);
        Assert::assertEquals(1, $set->errorCount());
        Assert::assertCount(1, $set);
    }

    public function testAddingSkippedTestIncreasesSkippedCount()
    {
        $set = $this->createSet();
        $result = $this->createResult();
        $result->skip();

        Assert::assertEquals(0, $set->skippedCount());
        Assert::assertCount(0, $set);
        $set->add($this->testcase(), $result);
        Assert::assertEquals(1, $set->skippedCount());
        Assert::assertCount(1, $set);
    }

    public function testAddingSuccessfulTestIncreasesSuccessCount()
    {
        $set = $this->createSet();
        $result = $this->createResult();

        Assert::assertEquals(0, $set->successCount());
        Assert::assertCount(0, $set);
        $set->add($this->testcase(), $result);
        Assert::assertEquals(1, $set->successCount());
        Assert::assertCount(1, $set);
    }

    private function createSet()
    {
        return new DefaultResultSet();
    }

    private function createResult()
    {
        return new DefaultTestResult();
    }

    private function testcase()
    {
        return \Mockery::mock('Demeanor\\TestCase');
    }
}
