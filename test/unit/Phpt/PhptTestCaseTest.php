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
    }

    public function testPhptFileWithoutTestSectionCreatesErroredResult()
    {
        $tc = new PhptTestCase(__DIR__.'/../Fixtures/notest.phpt');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->errored());
    }

    public function testPhptWithoutFileSectionCreatesErroredResult()
    {
        $tc = new PhptTestCase(__DIR__.'/../Fixtures/nofile.phpt');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->errored());
    }

    public function testPhptWithoutExpectAndExpectfCreatesErroredResult()
    {
        $tc = new PhptTestCase(__DIR__.'/../Fixtures/noexpect.phpt');
        $result = $this->runTest($tc);

        Assert::assertTrue($result->errored());
    }

    private function runTest(PhptTestCase $tc)
    {
        return $tc->run($this->emitter);
    }
}
