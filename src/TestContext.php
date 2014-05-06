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
 * Test context objects are optionally passed into testcases and used for the
 * testcase to pass information back to the test runner and the deamnor application
 * as a whole. This information also includes whether or not the test was skipped,
 * passed, or failed.
 *
 * This is pretty heavily inspired by Go's `testing` library and the `testing.T`
 * object found there.
 *
 * @since   0.1
 */
interface TestContext
{
    /**
     * Log some information to the text context. This may or may not be displayed
     * after the test finishes.
     *
     * @since   0.1
     * @param   string $message
     * @return  void
     */
    public function log($message);

    /**
     * Mark the test as failed with an optional message. This immediately stops
     * test execution.
     *
     * @since   0.1
     * @param   string $message
     * @throws  Demeanor\Exception\TestFailed
     * @return  void
     */
    public function fail($message='');

    /**
     * Mark the test as skipped with an optional message. This immediately stops
     * execution.
     *
     * @since   0.1
     * @param   string $message
     * @throws  Demeanor\Exception\TestSkipped
     * @return  void
     */
    public function skip($message='');
}
