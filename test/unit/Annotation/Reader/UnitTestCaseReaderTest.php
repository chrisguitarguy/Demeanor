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

class UnitTestCaseReaderTest
{
    use \Counterpart\Assert;

    private $testcase;
    private $reader;

    public function __construct()
    {
        $this->testcase = \Mockery::mock('Demeanor\\Unit\\UnitTestCase');
        $this->reader = new UnitTestCaseReader();
    }

    public function testDocblocksForReturnsDocblocksForReadsAllDocblocksFromClassesAndMethod()
    {
        $this->withReflectionClass();
        $this->withReflectionMethod();

        $docblocks = $this->reader->docblocksFor($this->testcase);

        $this->assertCount(3, $docblocks);
    }

    private function withReflectionClass()
    {
        $this->testcase->shouldReceive('getReflectionClass')
            ->atLeast(1)
            ->andReturn($this->createReflectionClass());
    }

    private function withReflectionMethod()
    {
        $this->testcase->shouldReceive('getReflectionMethod')
            ->atLeast(1)
            ->andReturn($this->createReflectionMethod());
    }

    private function createReflectionClass()
    {
        return new \ReflectionClass('Demeanor\\Fixtures\\DocblockChild');
    }

    public function createReflectionMethod()
    {
        return new \ReflectionMethod(
            'Demeanor\\Fixtures\\DocblockChild',
            'method'
        );
    }
}
