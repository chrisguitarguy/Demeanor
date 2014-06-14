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
use Demeanor\Finder\Finder;
use Demeanor\Coverage\Collector;
use Demeanor\Coverage\Report\ReportFactory;

/**
 * Collects code coverage on a test case.
 *
 * @since   0.3
 */
class CoverageSubscriber implements Subscriber
{
    private $finder;
    private $enabled;
    private $reports;
    private $coverageCollector;

    public function __construct($enabled, Finder $finder, array $reports)
    {
        $this->enabled = (bool)$enabled;
        $this->finder = $finder;
        $this->reports = $reports;
        $this->coverageCollector = new Collector();
    }

    public function getSubscribedEvents()
    {
        if (!$this->enabled) {
            return [];
        }

        return [
            Events::BEFORERUN_TESTCASE  => ['startCoverage', -1000],
            Events::AFTERRUN_TESTCASE   => ['stopCoverage', 1000],
            Events::TEARDOWN_ALL        => 'writeReports',
        ];
    }

    public function startCoverage(TestRunEvent $event)
    {
        $this->coverageCollector->start($event->getTestCase());
    }

    public function stopCoverage(TestRunEvent $event)
    {
        $this->coverageCollector->finish($event->getTestCase());
    }

    public function writeReports()
    {
        $files = $this->finder->all();
        foreach ($this->reports as $type => $output) {
            $report = ReportFactory::create($type, $output);
            $report->render($this->coverageCollector, $files);
        }
    }
}
