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

/**
 * A `Subscriber` defines a collection of listeners hidden behind a name (class),
 * which is nice so to clarify why listeners exist as well as give them a place
 * to reside.
 *
 * @since   0.1
 */
interface Subscriber
{
    /**
     * Get the events to which the subscriber will listen. Should return an
     * array with string keys and method names as the values.
     *
     * Examples:
     *
     *  - ['someEvent' => 'aMethod']
     *  - ['someEvent' => ['aMethod', 0]] // zero is the priority
     *  - ['someEvent' => [
     *      ['aMethod', 10],
     *      ['aNotherMethod', 11]
     *  - ]
     *
     * @since   0.1
     * @return  array
     */
    public function getSubscribedEvents();
}
