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

class AbstractTestCaseTest
{
    private $testcase;

    public function __construct()
    {
        $this->testcase = \Mockery::mock('Demeanor\\AbstractTestCase[getName,doRun]');
    }

    /**
     * @Expect(exception="Demeanor\Exception\DemeanorException")
     */
    public function testWithProviderThrowsExceptionWhenGivenANonArrayOrTraversable()
    {
        $this->testcase->withProvider('not an array');
    }

    public function testWithProviderMarksTheTestCaseHasHavingAProvider()
    {
        Assert::assertFalse($this->testcase->hasProvider(), 'test case should not have a provider before one is set');
        $this->testcase->withProvider(['one', 'two']);
        Assert::assertTrue($this->testcase->hasProvider(), 'test case should have a provider after one is set');
    }
}
