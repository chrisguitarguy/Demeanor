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
 * @since   0.1
 */
interface TestCase
{
    /**
     * Run the test case and return a result. This should never throw.
     *
     * @since   0.1
     * @param   Emitter $emitter
     * @return  TestResult
     */
    public function run(Emitter $emitter);

    /**
     * Get a pretty, printable version of the test's name.
     *
     * @since   0.1
     * @return  string
     */
    public function getName();

    /**
     * Set the expected exception that the TestCase will encounter along its
     * run.
     *
     * @since   0.1
     * @param   string $exceptionClass
     * @return  void
     */
    public function setExpectedException($exceptionClass);
}
