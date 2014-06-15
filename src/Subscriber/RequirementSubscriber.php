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
use Demeanor\Requirement\Requirements;

class RequirementSubscriber implements Subscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::BEFORE_TESTCASE     => ['setupRequirements', 1000],
            Events::BEFORERUN_TESTCASE  => ['checkRequirements', -1000],
        ];
    }

    /**
     * Set up the `Requirements` collection on the test context.
     *
     * @since   0.1
     * @param   TestRunEvent $event
     * @return  void
     */
    public function setupRequirements(TestRunEvent $event)
    {
        $context = $event->getTestContext();
        $context['requirements'] = new Requirements();
    }

    /**
     * Check the requirements for a given test case and skip the test if they
     * are not met.
     *
     * @since   0.1
     * @param   TestRunEvent $event
     * @return  void
     */
    public function checkRequirements(TestRunEvent $event)
    {
        $context = $event->getTestContext();
        if (!isset($context['requirements']) || !count($context['requirements'])) {
            return;
        }

        foreach ($context['requirements'] as $requirement) {
            if (!$requirement->met()) {
                $context->skip((string)$requirement);
            }
        }
    }
}
