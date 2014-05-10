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
 * Takes care of printing stuff to the screen.
 *
 * @since   0.1
 */
interface OutputWriter
{
    const VERBOSITY_QUIET   = 0; // no logs, error, or skip messages, just results
    const VERBOSITY_NORMAL  = 1; // only print error/skip messages and result
    const VERBOSITY_VERBOSE = 2; // print everything

    /**
     * Print $message to the screen followed by a new line.
     *
     * @since   0.1
     * @param   string $message
     * @param   int $verbosity level of the message
     * @return  void
     */
    public function writeln($message, $verbosity=self::VERBOSITY_NORMAL);

    /**
     * Write a test result to the screen.
     *
     * @since   0.1
     * @param   TestCase $testcase
     * @param   TestResult $result
     * @return  void
     */
    public function writeResult(TestCase $testcase, TestResult $result);
}
