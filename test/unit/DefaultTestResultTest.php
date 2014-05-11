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

class DefaultTestResultTest
{
    const MSG_TYPE = 'a_type';

    public function testTestResultIsSuccessfulByDefault()
    {
        Assert::assertTrue($this->createTestResult()->successful());
    }

    public function testFailMarksTestResultAsFailed()
    {
        $result = $this->createTestResult();
        Assert::assertFalse($result->failed());
        $result->fail();
        Assert::assertTrue($result->failed());
    }

    public function testSkipMarksTestResultAsSkipped()
    {
        $result = $this->createTestResult();
        Assert::assertFalse($result->skipped());
        $result->skip();
        Assert::assertTrue($result->skipped());
    }

    public function testErrorMarksTestAsErrored()
    {
        $result = $this->createTestResult();
        Assert::assertFalse($result->errored());
        $result->error();
        Assert::assertTrue($result->errored());
    }

    public function testMessagesAreReturnedFromGetMessagesIfTheyAreAdded()
    {
        $result = $this->createTestResult();
        $result->addMessage(self::MSG_TYPE, 'a message');

        $messages = $result->getMessages();
        Assert::assertType('array', $messages);
        Assert::assertCount(1, $messages);
    }

    private function createTestResult()
    {
        return new DefaultTestResult();
    }
}
