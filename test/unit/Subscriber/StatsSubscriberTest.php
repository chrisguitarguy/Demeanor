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

namespace Demeanor\Subscriber;

use Counterpart\Assert;

class StatsSubscriberTest
{
    private $outputWriter;
    private $subscriber;

    public function __construct()
    {
        $this->outputWriter = \Mockery::mock('Demeanor\\Output\\OutputWriter');
        $this->subscriber = new StatsSubscriber($this->outputWriter);
    }

    public function testGetSubscribedEventsReturnsArray()
    {
        Assert::assertType('array', $this->subscriber->getSubscribedEvents());
    }

    public function testStartStopLifeCycleWritesMemoryUsageToOutputWriter()
    {
        $this->outputWriter->shouldReceive('writeln')
            ->once()
            ->with(\Mockery::on(function ($val) {
                return false !== stripos($val, 'time') && false !== stripos($val, 'memory');
            }));

        $this->subscriber->start();
        $this->subscriber->stop();
    }

    public function testStopWithoutStartDoesNothing()
    {
        $this->outputWriter->shouldReceive('writeln')
            ->never();

        $this->subscriber->stop();
    }
}
