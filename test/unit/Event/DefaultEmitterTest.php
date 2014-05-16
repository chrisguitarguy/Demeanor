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

namespace Demeanor\Event;

use Counterpart\Assert;
use Demeanor\TestContext;

class DefaultEmitterTest
{
    const EVENT = 'an_event';

    public function testHasListenersReturnsTrueOnlyIfListenersHaveBeenAddedToEvent()
    {
        $em = $this->createEmitter();
        Assert::assertFalse($em->hasListeners(self::EVENT));
        $em->addListener(self::EVENT, function () { });
        Assert::assertTrue($em->hasListeners(self::EVENT));
    }

    /**
     * This really just makes sure we don't cause any warnings from invalid
     * array keys or whatever in the lines of code that follow the listener
     * check
     */
    public function testEventWithoutListenersDoesNothing()
    {
        $this->createEmitter()->emit('an_event');
    }

    public function testCallingStopOnEventCausesSequentListenersToBeIgnored()
    {
        $em = $this->createEmitter();
        $firstListener = false;
        $secondListener = false;
        $em->addListener(self::EVENT, function (Event $e) use (&$firstListener) {
            $e->stop();
            $firstListener = true;
        }, 10);
        $em->addListener(self::EVENT, function () use (&$secondListener) {
            $secondListener = true;
        }, 9);

        $em->emit(self::EVENT);

        Assert::assertTrue($firstListener, 'Emitter should have called the first listener');
        Assert::assertFalse($secondListener, "Emitter shouldn't have called the second listener");
    }

    public static function subscriberReturnProvider()
    {
        return [
            'methodOnly'        => ['onEvent'],
            'methodAndPriority' => [['onEvent', 10]],
            'multipleCallbacks' => [[
                ['onEvent', 10]
            ]],
        ];
    }

    /**
     * @Provider(method="subscriberReturnProvider")
     */
    public function testAddSubscriberAddsCorrectListeners(TestContext $ctx, $listenerVal)
    {
        $em = $this->createEmitter();
        $subscriber = $this->subscriberReturning([
            self::EVENT     => $listenerVal,
        ]);

        Assert::assertFalse($em->hasListeners(self::EVENT));
        $em->addSubscriber($subscriber);
        Assert::assertTrue($em->hasListeners(self::EVENT));
    }

    private function createEmitter()
    {
        return new DefaultEmitter();
    }

    private function subscriberReturning(array $events)
    {
        $sub = \Mockery::mock('Demeanor\\Event\\Subscriber');
        $sub->shouldReceive('getSubscribedEvents')
            ->once()
            ->andReturn($events);

        return $sub;
    }
}
