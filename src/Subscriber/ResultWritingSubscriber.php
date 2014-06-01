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
use Demeanor\Event\TestRunEvent;
use Demeanor\Event\TestSuiteEvent;
use Demeanor\Output\OutputWriter;

/**
 * Handles writing results to some sort of output
 *
 * @since   0.2
 */
class ResultWritingSubscriber implements Subscriber
{
    /**
     * The actual output writer.
     *
     * @since   0.2
     * @var     OutputWriter
     */
    private $outputWriter;

    /**
     * constructor. Set up the output writer.
     *
     * @since   0.2
     * @param   OutputWriter $writer
     * @return  void
     */
    public function __construct(OutputWriter $writer)
    {
        $this->outputWriter = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::BEFORE_TESTSUITE    => 'writeSuiteHeader',
            Events::AFTER_TESTSUITE     => 'writeSuiteSummary',
            Events::AFTER_TESTCASE      => 'writeResult',
        ];
    }

    public function writeSuiteHeader(TestSuiteEvent $event)
    {
        $this->outputWriter->writeln(sprintf('Running test suite "%s"', $event->getTestSuite()->name()));
    }

    public function writeSuiteSummary(TestSuiteEvent $event)
    {
        $this->outputWriter->writeln('');
    }

    public function writeResult(TestRunEvent $event)
    {
        $this->outputWriter->writeResult(
            $event->getTestCase(),
            $event->getTestResult()
        );
    }
}
