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
use Demeanor\StackTrace\StackTraceFilter;
use Demeanor\StackTrace\FirstExternalStackTraceFilter;
use Demeanor\StackTrace\FileStackTraceFilter;

/**
 * Listens in for assertion failures and unexpected exceptions and adds pretty
 * error messages to the `TestResult`
 *
 * @since   0.2
 */
class ExceptionSubscriber implements Subscriber
{
    private $assertionFilter;
    private $exceptionFilter;

    /**
     * Constructor. Optionally set up StackTraceFilter objects.
     *
     * @since   0.2
     * @param   StackTraceFilter $assertionFilter
     * @return  void
     */
    public function __construct(StackTraceFilter $assertionFilter=null, StackTraceFilter $exceptionFilter=null)
    {
        $this->assertionFilter = $assertionFilter ?: new FirstExternalStackTraceFilter('Counterpart\\Assert');
        $this->exceptionFilter = $exceptionFilter ?: new FileStackTraceFilter();
    }

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
            'Caught unexpected %s(%s): %s',
            get_class($except),
            $except->getCode(),
            $except->getMessage()
        ));
        $result->addMessage('error', $this->exceptionFilter->traceToString($except));
    }

    public function onAssertionError(TestExceptionEvent $event)
    {
        $except = $event->getException();
        $result = $event->getTestResult();
        $result->addMessage('fail', sprintf(
            '%s, %s',
            $except->getMessage(),
            $this->assertionFilter->traceToString($except) ?: 'Unknown Location'
        ));
    }
}
