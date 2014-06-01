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
 * Represents a test suite (collection of test cases).
 *
 * @since   0.1
 */
interface TestSuite
{
    /**
     * locate all the test files and turn them into `TestCase` instances.
     *
     * @since   0.1
     * @return  TestCase[]
     */
    public function load();

    /**
     * Bootstrap the test suite (include all the files required for it).
     *
     * @since   0.1
     * @return  void
     */
    public function bootstrap();

    /**
     * Get the name of the test suite.
     *
     * @since   0.1
     * @return  string
     */
    public function name();

    /**
     * Run the test suite, writting results to `OutputWriter`
     *
     * @since   0.1
     * @param   Emitter $emitter
     * @param   OutputWriter $output
     * @return  ResultSet
     */
    public function run(Emitter $emitter);
}
