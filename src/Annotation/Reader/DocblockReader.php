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

namespace Demeanor\Annotation\Reader;

use Demeanor\TestCase;

/**
 * "Reads" docblocks from a test case.
 *
 * @since   0.5
 */
interface DocblockReader
{
    /**
     * Locate and return the docblocks from a test case.
     *
     * @since   0.5
     * @param   TestCase $testcase
     * @throws  Demeanor\Exception\InvalidArgumentException if the testcase type
     *          is not supported.
     * @return  string[] The array of docblocks to read
     */
    public function docblocksFor(TestCase $testcase);

    /**
     * Check to see if the implementation supports a given test case.
     *
     * @since   0.5
     * @param   TestCase $testcase
     * @return  boolean
     */
    public function supports(TestCase $testcase);
}
