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

namespace Demeanor\Annotation;

use Demeanor\TestContext;
use Demeanor\TestResult;
use Demeanor\Unit\UnitTestCase;

/**
 * ABC for "callback" annotation that add before/after callbacks to the testcase
 *
 * @since   0.1
 */
abstract class Callback extends Annotation
{
    /**
     * {@inheritdoc}
     */
    public function attachRun(UnitTestCase $testcase, TestContext $context, TestResult $result)
    {
        $callable = null;
        if ($this->hasValidMethod($testcase)) {
            $callable = [$testcase->getTestObject(), $this->args['method']];
        } elseif ($this->hasValidFunction($testcase)) {
            $callable = $this->normalizeName($this->args['function']);
        }

        if ($callable) {
            $this->attachCallable($testcase, $callable);
        }
    }

    /**
     * Actually attach the callback to the test case.
     *
     * @since   0.1
     * @param   UnitTestCase $testcase
     * @param   callable $callable
     * @return  void
     */
    abstract protected function attachCallable(UnitTestCase $testcase, callable $callable);
}