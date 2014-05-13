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

use Demeanor\Exception\TestFailed;
use Demeanor\Exception\TestSkipped;

class DefaultTestContext extends \ArrayObject implements TestContext
{
    private $testcase;
    private $result;

    public function __construct(TestCase $testcase, TestResult $result)
    {
        $this->testcase = $testcase;
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function log($message)
    {
        $this->addMessage('log', $message);
    }

    /**
     * {@inheritdoc}
     */
    public function fail($message='')
    {
        $this->addMessage('fail', $message);
        throw new TestFailed($message);
    }

    /**
     * {@inheritdoc}
     */
    public function skip($message='')
    {
        $this->addMessage('skip', $message);
        throw new TestSkipped($message);
    }

    /**
     * {@inheritdoc}
     */
    public function expectException($exceptionClass)
    {
        $this->testcase->setExpectedException($exceptionClass);
    }

    private function addMessage($messageType, $message)
    {
        if (!$message) {
            return;
        }
        $this->result->addMessage($messageType, $message);
    }
}
