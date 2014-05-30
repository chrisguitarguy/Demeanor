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

use Demeanor\Events;
use Demeanor\Event\Subscriber;
use Demeanor\Event\TestExceptionEvent;

/**
 * Listens in for assertion failures and unexpected exceptions and adds pretty
 * error messages to the `TestResult`
 *
 * @since   0.2
 */
class ExceptionSubscriber implements Subscriber
{
    private $assertReflect = null;

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::EXCEPTION_TESTCASE  => 'onException',
            Events::ASSERTION_TESTCASE  => 'onAssertionError',
        ];
    }

    public function onException(TestExceptionEvent $event)
    {
        $except = $event->getException();
        $result = $event->getTestResult();
        $result->addMessage('error', sprintf(
            'Caught unexpected %s exception: %s',
            get_class($except),
            $except->getMessage()
        ));
        $result->addMessage('error', $except->getTraceAsString());
    }

    public function onAssertionError(TestExceptionEvent $event)
    {
        $except = $event->getException();
        $result = $event->getTestResult();
        $where = $this->locateAssertion($except);
        $result->addMessage('fail', sprintf(
            '%s, %s',
            $except->getMessage(),
            $where ?: 'Unknown Location'
        ));
    }

    private function locateAssertion(\Exception $e)
    {
        $fn = dirname($this->getCounterpartReflection()->getFileName());

        $loc = null;
        foreach ($e->getTrace() as $frame) {
            if (isset($frame['line']) && isset($frame['file']) && dirname($frame['file']) != $fn) {
                $loc = sprintf('%s:%s', $frame['file'], $frame['line']);
                break;
            }
        }

        return $loc;
    }

    private function getCounterpartReflection()
    {
        if (null === $this->assertReflect) {
            $this->assertReflect = new \ReflectionClass('Counterpart\\Assert');
        }

        return $this->assertReflect;
    }
}
