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
 * A collection of `TestResult` object and their corresponeding tests.
 *
 * @since   0.1
 */
interface ResultSet extends \Countable
{
    /**
     * Add a test+result pair to the set.
     *
     * @since   0.1
     * @param   TestCase $test
     * @param   TestResult $result
     * @return  void
     */
    public function add(TestCase $test, TestResult $result);

    /**
     * Get the count of error tests.
     *
     * @since   0.1
     * @return  int
     */
    public function errorCount();

    /**
     * Get all the tests with errors.
     *
     * @since   0.2
     * @return  SplObjectStorage with test cases as "keys"
     */
    public function errors();

    /**
     * Get the count of failed tests.
     *
     * @since   0.1
     * @return  int
     */
    public function failedCount();

    /**
     * Get all the tests that failed.
     *
     * @since   0.2
     * @return  SplObjectStorage with test cases as "keys"
     */
    public function failures();

    /**
     * Get teh count of skipped tests.
     *
     * @since   0.1
     * @return  int
     */
    public function skippedCount();

    /**
     * Get the count of successful tests.
     *
     * @since   0.1
     * @return  int
     */
    public function successCount();

    /**
     * Get the count of filtered tests.
     *
     * @since   0.2
     * @return  int
     */
    public function filteredCount();

    /**
     * Check to see if the result set is successful (has no errors and no failures).
     *
     * @since   0.1
     * @return  boolean
     */
    public function successful();
}
