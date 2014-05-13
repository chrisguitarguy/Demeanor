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
 * Interface for events. Generally you don't typehint against this, it's just
 * used as a "marker" with a few methods for the emitter to see if it can continue
 *
 * @since   0.1
 */
interface Event
{
    /**
     * Stop the event from continuing on to subsequent listeners.
     *
     * @since   0.1
     * @return  void
     */
    public function stop();

    /**
     * Check to see if the event has been stopped.
     *
     * @since   0.1
     * @return  boolean
     */
    public function stopped();
}
