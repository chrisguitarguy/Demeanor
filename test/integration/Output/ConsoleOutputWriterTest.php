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

namespace Demeanor\Output;

use Counterpart\Assert;
use Symfony\Component\Console\Output\StreamOutput;
use Demeanor\DefaultTestResult;

class ConsoleOutputWriterTest
{
    private $stream;
    private $consoleOutput;
    private $writer;

    public function __construct()
    {
        $this->stream = tmpfile();
        $this->consoleOutput = new StreamOutput($this->stream);
        $this->writer = new ConsoleOutputWriter($this->consoleOutput);
    }

    public function testWritelnSendsMessageToConsoleOutput()
    {
        $this->writer->writeln('here');
        $this->assertStreamContains('here');
    }

    public function testWriteResultWithFilteredTestDoesNothing()
    {
        $tc = $this->testCase('a test');
        $tr = $this->createResult();
        $tr->filter();

        $this->writer->writeResult($tc, $tr);

        Assert::assertEmpty($this->streamContents());
    }

    private function assertStreamContains($value)
    {
        Assert::assertStringContains($value, $this->streamContents());
    }

    private function streamContents()
    {
        fseek($this->stream, 0);
        $content = '';
        while (!feof($this->stream)) {
            $content .= fread($this->stream, 4096);
        }

        return $content;
    }

    private function createResult()
    {
        return new DefaultTestResult();
    }

    private function testCase($name)
    {
        $tc = \Mockery::mock('Demeanor\\TestCase');
        $tc->shouldReceive('getName')
            ->andReturn($name);

        return $tc;
    }
}
