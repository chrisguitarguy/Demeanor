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
use Demeanor\DefaultTestResult;
use Demeanor\DefaultTestContext;
use Demeanor\Event\TestRunEvent;

class FilterSubscriberTest
{
    private $filter;
    private $subscriber;

    public function __construct()
    {
        $this->filter = \Mockery::mock('Demeanor\\Filter\\Filter');
        $this->subscriber = new FilterSubscriber($this->filter);
    }

    public function testGetSubscribedEventsReturnsArray()
    {
        Assert::assertType('array', $this->subscriber->getSubscribedEvents());
    }

    public function testFailingFilterMarksTestAsFiltered()
    {
        $this->filterReturns(false);

        $event = $this->createEvent();
        $this->subscriber->filterTestCase($event);
        Assert::assertTrue($event->getTestResult()->filtered());
    }

    public function testPassingFilterDoesNothingToTestCase()
    {
        $this->filterReturns(true);
        $event = $this->createEvent();
        $this->subscriber->filterTestCase($event);
        Assert::assertTrue($event->getTestResult()->successful());
    }

    private function filterReturns($bool)
    {
        $this->filter->shouldReceive('canRun')
            ->atLeast(1)
            ->andReturn($bool);
    }

    private function createEvent()
    {
        $tc = $this->createTestCase();
        $result = new DefaultTestResult();
        return new TestRunEvent(
            $tc,
            new DefaultTestContext($tc, $result),
            $result
        );
    }

    private function createTestCase()
    {
        return \Mockery::mock('Demeanor\\TestCase');
    }
}
