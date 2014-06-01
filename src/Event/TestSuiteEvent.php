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

use Demeanor\TestSuite;
use Demeanor\ResultSet;

/**
 * An event that's aware of a test suite
 *
 * @since   0.2
 */
class TestSuiteEvent extends DefaultEvent
{
    private $testsuite;
    private $results;

    public function __construct(TestSuite $suite, ResultSet $results=null)
    {
        $this->testsuite = $suite;
        $this->results = $results;
    }

    public function getTestSuite()
    {
        return $this->testsuite;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function hasResults()
    {
        return null !== $this->getResults();
    }
}
