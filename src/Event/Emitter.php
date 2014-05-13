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
 * Defines an event "emitter" -- something that manages listeners and sends
 * events.
 *
 * @since   0.1
 */
interface Emitter
{
    const DEFAULT_PRIORITY = 256;

    /**
     * Send an event to its listeners.
     *
     * @since   0.1
     * @param   string $eventName
     * @param   Event $event
     * @return  void
     */
    public function emit($eventName, Event $event=null);

    /**
     * Add a single listener to an event.
     *
     * @since   0.1
     * @param   string $eventName
     * @param   callable $listener
     * @param   int $priority
     * @return  void
     */
    public function addListener($eventName, callable $listener, $priority=self::DEFAULT_PRIORITY);

    /**
     * Add an event subscriber to the emitter.
     *
     * @since   0.1
     * @param   Subscriber $subscriber
     * @return  void
     */
    public function addSubscriber(Subscriber $subscriber);

    /**
     * Check to see if an event has listeners.
     *
     * @since   0.1
     * @param   string $eventName
     * @return  boolean
     */
    public function hasListeners($eventName);
}
