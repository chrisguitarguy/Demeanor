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

namespace Demeanor\Event;

use Demeanor\TestCase;
use Demeanor\TestContext;
use Demeanor\TestResult;

/**
 * An event that's aware of all of the test objects along with an exception. Used
 * when something goes wrong with a test case (like an assertion failure or an
 * unexpected exception).
 *
 * @since   0.2
 */
class TestExceptionEvent extends TestRunEvent
{
    private $exception;

    public function __construct(TestCase $testCase, TestContext $context, TestResult $result, \Exception $e)
    {
        parent::__construct($testCase, $context, $result);
        $this->exception = $e;
    }

    public function getException()
    {
        return $this->exception;
    }
}
