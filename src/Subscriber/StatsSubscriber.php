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

use Symfony\Component\Stopwatch\Stopwatch;
use Demeanor\Events;
use Demeanor\Event\Subscriber;
use Demeanor\Output\OutputWriter;

/**
 * Collects stats on demeanors overall run -- includes total time and memory
 * usage.
 *
 * @since   0.3
 */
class StatsSubscriber implements Subscriber
{
    const CATEGORY      = 'demeanor';
    const OVERALL       = 'overallrun';
    const BYTESPERMB    = 1048576;

    private $stopwatch;
    private $outputWriter;

    public function __construct(OutputWriter $output, Stopwatch $stopwatch=null)
    {
        $this->outputWriter = $output;
        $this->stopwatch = $stopwatch ?: new Stopwatch();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::SETUP_ALL       => 'start',
            Events::TEARDOWN_ALL    => ['stop', 300],
        ];
    }

    public function start()
    {
        $this->stopwatch->start(self::OVERALL, self::CATEGORY);
    }

    public function stop()
    {
        if (!$this->stopwatch->isStarted(self::OVERALL)) {
            return;
        }

        $se = $this->stopwatch->stop(self::OVERALL);
        $this->outputWriter->writeln(sprintf(
            'Time: %.3f seconds, Memory: %.3f',
            $se->getDuration() / 1000,
            $se->getMemory() / self::BYTESPERMB
        ));
    }
}
