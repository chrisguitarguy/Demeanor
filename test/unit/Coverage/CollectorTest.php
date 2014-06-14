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

namespace Demeanor\Coverage;

use Counterpart\Assert;

class CollectorTest
{
    private $driver;
    private $collector;

    public function __construct()
    {
        $this->driver = \Mockery::mock('Demeanor\\Coverage\\Driver\\Driver');
        $this->collector = new Collector($this->driver);
    }

    public function testStartProxiesToDriver()
    {
        $this->driver->shouldReceive('start')
            ->once();

        $this->collector->start($this->testCase());
    }

    public function testFinishWithNonExistentFileDoesNotAddAnyCoverage()
    {
        $this->driver->shouldReceive('finish')
            ->once()
            ->andReturn([__DIR__.'/does/not/exist' => [1,2]]);

        $this->collector->finish($this->testCase());

        Assert::assertCount(0, $this->collector->getIterator());
    }

    public function testFinishWithExistingFileAddsLinesToCoverageReport()
    {
        $this->driver->shouldReceive('finish')
            ->once()
            ->andReturn([
                __FILE__    => [1, 2],
            ]);

        $this->collector->finish($this->testCase());

        $iter = $this->collector->getIterator();
        Assert::assertCount(1, $iter);
    }

    private function testCase()
    {
        return \Mockery::mock('Demeanor\\TestCase');
    }
}