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

use Demeanor\TestCaseStub;

class ChainReaderTest
{
    use \Counterpart\Assert;

    public function testSupportsFailsWhenNoSubReadersSupportTestCase()
    {
        $reader = $this->reader();
        $this->readerDoesNotSupport($reader);
        $reader2 = $this->reader();
        $this->readerDoesNotSupport($reader2);
        $chain = new ChainReader([$reader, $reader2]);

        $this->assertFalse($chain->supports($this->testcase()));
    }

    public function testSupportsSucceedsWhenOneSubreaderSupportsTestCase()
    {
        $reader = $this->reader();
        $this->readerDoesNotSupport($reader);
        $reader2 = $this->reader();
        $this->readerSupports($reader2);
        $chain = new ChainReader([$reader, $reader2]);

        $this->assertTrue($chain->supports($this->testcase()));
    }

    public function testDocblocksForCombinesDocblocksFromAllSupportingReaders()
    {
        $reader = $this->reader();
        $this->readerDoesNotSupport($reader);
        $reader2 = $this->reader();
        $this->readerSupports($reader2);
        $this->withDocblocks($reader2, ['one', 'two']);
        $chain = new ChainReader([$reader, $reader2]);

        $docblocks = $chain->docblocksFor($this->testcase());

        $this->assertEquals(['one', 'two'], $docblocks);
    }

    public function testDocblocksForReturnsEmptyWhenNoReadersSupport()
    {
        $reader = $this->reader();
        $this->readerDoesNotSupport($reader);
        $reader2 = $this->reader();
        $this->readerDoesNotSupport($reader2);
        $chain = new ChainReader([$reader, $reader2]);

        $docblocks = $chain->docblocksFor($this->testcase());

        $this->assertType('array', $docblocks);
        $this->assertEmpty($docblocks);
    }

    private function reader()
    {
        return \Mockery::mock('Demeanor\\Annotation\\Reader\\DocblockReader');
    }

    private function readerSupports($reader)
    {
        $reader->shouldReceive('supports')
            ->andReturn(true);
    }

    private function readerDoesNotSupport($reader)
    {
        $reader->shouldReceive('supports')
            ->andReturn(false);
    }

    private function withDocblocks($reader, array $docblocks)
    {
        $reader->shouldReceive('docblocksFor')
            ->andReturn($docblocks);
    }

    private function testcase()
    {
        return new TestCaseStub();
    }
}
