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

namespace Demeanor\Annotation\Reader;

use Demeanor\TestCase;
use Demeanor\TestCaseStub;

class _AbstractReaderSpy extends AbstractReader
{
    use \Counterpart\Assert;

    private $supports;
    private $testcase = null;

    public function __construct($supports)
    {
        $this->supports = (bool) $supports;
    }

    public function supports(TestCase $testcase)
    {
        return $this->supports;
    }

    public function assertCalledWith($testcase)
    {
        $this->assertIdentical($testcase, $this->testcase);
    }

    protected function readDocblocks(TestCase $testcase)
    {
        $this->testcase = $testcase;
    }
}

class AbstractReaderTest
{
    const SUPPORTS = true;
    const NO_SUPPORT = false;

    use \Counterpart\Assert;

    /**
     * @Expect("Demeanor\Exception\InvalidArgumentException")
     */
    public function testUnsupportedTestCaseCausesError()
    {
        $reader = new _AbstractReaderSpy(self::NO_SUPPORT);

        $reader->docblocksFor($this->testcase());
    }

    public function testSupportTestcaseReadsDocblocksFromTestCaseObject()
    {
        $reader = new _AbstractReaderSpy(self::SUPPORTS);
        $testcase = $this->testcase();

        $reader->docblocksFor($testcase);

        $reader->assertCalledWith($testcase);
    }

    private function testcase()
    {
        return new TestCaseStub();
    }
}
