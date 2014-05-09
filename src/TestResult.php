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

namespace Demeanor;

/**
 * Represents the result of a test -- including it's status (success, failed, or
 * skipped) and log messages.
 *
 * @since   0.1
 */
interface TestResult
{
    const STATUS_SUCCESS    = 0;
    const STATUS_FAILED     = 1;
    const STATUS_SKIPPED    = 2;
    const STATUS_ERROR      = 3;

    /**
     * Mark the test as failed.
     *
     * @since   0.1
     * @return  void
     */
    public function fail();

    /**
     * Returns whether or not the test has failed.
     *
     * @since   0.1
     * @return  boolean
     */
    public function failed();

    /**
     * Mark the test as skipped.
     *
     * @since   0.1
     * @return  void
     */
    public function skip();

    /**
     * Returns whether or not the test was skipped.
     *
     * @since   0.1
     * @return  boolean
     */
    public function skipped();

    /**
     * Mark the test as having an error.
     *
     * @since   0.1
     * @return  void
     */
    public function error();

    /**
     * Whether or not the test is in an error state.
     *
     * @since   0.1
     * @return  boolean
     */
    public function errored();

    /**
     * Add a log message.
     *
     * @since   0.1
     * @param   string $messageType The message type ('log', 'error', 'skip')
     * @param   string $message
     * @return  void
     */
    public function addMessage($messageType, $message);

    /**
     * Get all the messages.
     *
     * @since   0.1
     * @return  string[]
     */
    public function getMessages();

    /**
     * Get the test status code.
     *
     * @since   0.1
     * @return  int
     */
    public function getStatus();
}
