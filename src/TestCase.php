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

use Demeanor\Event\Emitter;

/**
 * Represents a single test case. What exactly that is up to the implementation.
 *
 * For example:
 *  1. UnitTestCase might run a single method in a class
 *  2. SpecTestCase might represent a single `describe` call (and possibly all
 *     of its nested `describe` calls.
 *  3. A StoryTestCase might be a single scenario
 *
 * Test cases know to run themselves and return a result. They also act as
 * containers for metadata about the test.
 *
 * @since   0.1
 */
interface TestCase extends Metadata
{
    /**
     * Run the test case and return a result. This should never throw.
     *
     * @since   0.1
     * @param   Emitter $emitter
     * @return  TestResult
     */
    public function run(Emitter $emitter, array $testArgs=[]);

    /**
     * Get a pretty, printable version of the test's name.
     *
     * @since   0.1
     * @return  string
     */
    public function getName();

    /**
     * Adds a textual "descriptor" to the test case, which is used when the
     * results are printed to the screen. These are things like "Data Set #0"
     * or "risky" or other things like that.
     *
     * @since   0.1
     * @param   string $descriptor
     * @return  void
     */
    public function addDescriptor($descriptor);

    /**
     * Get the test cases descriptors.
     *
     * @since   0.1
     * @return  string[]
     */
    public function getDescriptors();

    /**
     * Set the expected exception that the TestCase will encounter along its
     * run.
     *
     * @since   0.1
     * @param   string $exceptionClass
     * @return  void
     */
    public function willThrow($exceptionClass);

    /**
     * Set the data provider for the testcase.
     *
     * @since   0.1
     * @param   array|Traversable
     * @return  void
     */
    public function withProvider($provider);

    /**
     * Check to see whether the test case has a data provider.
     *
     * @since   0.1
     * @return  boolean
     */
    public function hasProvider();

    /**
     * Get the data provider.
     *
     * @since   0.1
     * @return  array|Traversable|null
     */
    public function getProvider();

    /**
     * Add a callable that will be run before test execution.
     *
     * @since   0.1
     * @param   callable $cb
     * @return  void
     */
    public function before(callable $cb);

    /**
     * Add a callable that will be run after test execution.
     *
     * @since   0.1
     * @param   callable $cb
     * @return  void
     */
    public function after(callable $cb);

    /**
     * Get the absolute path of the file in which the test resides.
     *
     * @since   0.2
     * @return  string
     */
    public function filename();

    /**
     * Get the line on which the test case is defined.
     *
     * @since   0.2
     * @return  int
     */
    public function lineno();
}
