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

namespace Demeanor\Spec;

class TestCaseCollection
{
    private $tests = array();

    /**
     * Put a new test case onto the list of already existing test cases.
     *
     * @since   0.1
     * @param   SpecTestCase $testcase
     * @return  void
     */
    public function put(SpecTestCase $testcase)
    {
        $this->tests[] = $testcase;
    }

    /**
     * Get all the test cases.
     *
     * @since   0.1
     * @return  SpecTestCase[]
     */
    public function all()
    {
        return $this->tests;
    }
}
