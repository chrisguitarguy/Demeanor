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

final class Events
{
    /**
     * Dispatched directly after the test method or callback is invoked, useful
     * for checking post conditions on the test.
     */
    const BEFORERUN_TESTCASE    = 'testcase.beforerun';

    /**
     * Dispatched directly before the test method or callback is invoked, useful
     * for checking things directly before the test case run (ala Requirements).
     */
    const AFTERRUN_TESTCASE     = 'testcase.afterrun';

    /**
     * Occurs directly after the `TestResult` and `TestContext` objects have
     * been created.
     */
    const BEFORE_TESTCASE       = 'testcase.before';

    /**
     * Dispatched after the test method/callback has been invoked, regardless of
     * whether an exception has been throws.
     */
    const AFTER_TESTCASE        = 'testcase.after';

    /**
     * Dispatched when an unexpected exception occurs.
     */
    const EXCEPTION_TESTCASE    = 'testcase.exception';

    /**
     * Run from the TestSuite before TestCase::run is invoked. This can't be used
     * to change the result of an individual test, but it's useful for doing
     * things like modifying the test case before it's run (like setting up data
     * providers, etc).
     *
     * Note that this is only run once for test cases that have data providers.
     */
    const SETUP_TESTCASE        = 'testcase.setup';

    /**
     * Run after the TestCase::run method is invoked. Can be used to do any
     * teardown necessary after the `run` method.
     */
    const TEARDOWN_TESTCASE     = 'testcase.teardown';

    private function __construct() { }
}
