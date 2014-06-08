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
use Demeanor\Filter\Filter;

/**
 * A subscriber that marks test as filtered based on a passed in filter.
 *
 * @since   0.2
 */
class FilterSubscriber implements Subscriber
{
    /**
     * The a filter that does all the checking.
     *
     * @since   0.2
     * @var     Filter
     */
    private $filter;

    /**
     * Set up the filter object.
     *
     * @since   0.2
     * @param   Filter $filter
     * @return  void
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::AFTER_TESTCASE  => 'filterTestCase',
        ];
    }

    public function filterTestCase(TestRunEvent $event)
    {
        if (!$this->filter->canRun($event->getTestCase())) {
            $event->getTestResult()->filter();
        }
    }
}
