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

namespace Demeanor\Extension\Annotation;

use Demeanor\TestContext;
use Demeanor\TestResult;
use Demeanor\Unit\UnitTestCase;

/**
 * An ABC for all the annotation used in this extenion. Annotation know how to
 * attach themselves to test suites.
 *
 * @since   0.1
 */
abstract class Annotation
{
    protected $args = array();

    /**
     * Costructor. Set up the arguments from the annotation parser.
     *
     * @since   0.1
     * @param   array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * Attach actions to the test case on setup.
     *
     * @since   0.1
     * @param   TestCase $testcase
     * @return  void
     */
    public function attachSetup(UnitTestCase $testcase)
    {

    }

    /**
     * Do whatever the annotation is meant to do with the test case. This is
     * called from a `TestRunEvent` that's aware of test results and contexts
     *
     * @since   0.1
     * @param   TestCase $testcase
     * @param   TestContext $context
     * @param   TestResult $result
     * @return  void
     */
    public function attachRun(UnitTestCase $testcase, TestContext $context, TestResult $result)
    {
        // noop by default, subclasses can do their thing
    }

    /**
     * Remove doubled backslashes and replace them with singles.
     *
     * @since   0.1
     * @param   string $ident
     * @return  string
     */
    protected function normalizeName($ident)
    {
        return str_replace('\\\\', '\\', $ident);
    }
}
