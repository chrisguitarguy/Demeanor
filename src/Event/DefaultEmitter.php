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
 * The default `Emitter` implementation.
 *
 * @since   0.1
 */
class DefaultEmitter implements Emitter
{
    /**
     * The array of listeners - this is an associative array with event names
     * as keys, and an array of priority => callable[] pairs as values.
     *
     * @since   0.1
     * @var     array
     */
    private $listeners = array();

    /**
     * {@inheritdoc}
     */
    public function emit($eventName, Event $event=null)
    {
        if (!$this->hasListeners($eventName)) {
            return;
        }

        $event = $event ?: new DefaultEvent();
        krsort($this->listeners[$eventName], SORT_NUMERIC);
        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            foreach ($listeners as $listener) {
                call_user_func($listener, $event);
                if ($event->stopped()) {
                    break 2;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, callable $callable, $priority=self::DEFAULT_PRIORITY)
    {
        if (!$this->hasListeners($eventName)) {
            $this->listeners[$eventName] = array();
        }

        $priority = intval($priority);
        if (!isset($this->listeners[$eventName][$priority])) {
            $this->listeners[$eventName][$priority] = array();
        }

        $this->listeners[$eventName][$priority][] = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(Subscriber $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $listeners) {
            if (is_string($listeners)) { // method name only
                $this->addListener($eventName, [$subscriber, $listeners]);
            } elseif (is_string($listeners[0])) {
                $this->addListener(
                    $eventName,
                    [$subscriber, $listeners[0]],
                    isset($listeners[1]) ? $listeners[1] : self::DEFAULT_PRIORITY
                );
            } else {
                foreach ($listeners as $listener) {
                    $this->addListener(
                        $eventName,
                        [$subscriber, $listener[0]],
                        isset($listener[1]) ? $listener[1] : self::DEFAULT_PRIORITY
                    );
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($eventName)
    {
        return isset($this->listeners[$eventName]);
    }
}
