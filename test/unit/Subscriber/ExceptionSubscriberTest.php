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
use Demeanor\Event\TestExceptionEvent;

class ExceptionSubscriberTest
{
    private $subscriber;

    public function __construct()
    {
        $this->subscriber = new ExceptionSubscriber();
    }

    public function testGetSubscribedEventsReturnsArray()
    {
        Assert::assertType('array', $this->subscriber->getSubscribedEvents());
    }

    public function testOnExceptionAddsMessagesToTestResult()
    {
        $event = $this->createEvent();
        $result = $event->getTestResult();

        $this->subscriber->onException($event);

        $msg = $result->getMessages();
        Assert::assertArrayHasKey('error', $msg);
        Assert::assertGreaterThan(0, count($msg['error']));
    }

    public function testOnAssertionErrorAddsMessagesToTestResult()
    {
        try {
            Assert::assertFalse(true);
        } catch (\Exception $e) {
            // pass
        }

        $event = $this->createEvent($e);
        $result = $event->getTestResult();

        $this->subscriber->onAssertionError($event);

        $msg = $result->getMessages();
        Assert::assertArrayHasKey('fail', $msg);
        Assert::assertGreaterThan(0, count($msg['fail']));
    }

    private function createEvent(\Exception $e=null)
    {
        $tc = $this->createTestCase();
        $result = new DefaultTestResult();
        return new TestExceptionEvent(
            $tc,
            new DefaultTestContext($tc, $result),
            $result,
            $e ?: new \Exception('broken')
        );
    }

    private function createTestCase()
    {
        return \Mockery::mock('Demeanor\\TestCase');
    }
}
