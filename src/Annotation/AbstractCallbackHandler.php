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

use Demeanor\TestCase;
use Demeanor\Unit\UnitTestCase;

/**
 * Base class for callback handlers.
 *
 * @since   0.5
 */
abstract class AbstractCallbackHandler extends AbstractHandler
{
    use MethodValidatorTrait;
    use FunctionValidatorTrait;
    use NameNormalizationTrait;

    /**
     * {@inheritdoc}
     */
    public function onRun(Annotation $annotation, TestCase $testcase)
    {
        if (!$this->isUnitTest($testcase)) {
            return;
        }


        $method = $first = $annotation->positional(0);
        if (!$method) {
            $method = $annotation->named('method');
        }

        if ($method && $this->isValidMethod($method, $testcase)) {
            return $this->attachCallable($testcase, [$testcase->getTestObject(), $method]);
        }

        $func = $first;
        if (!$func) {
            $func = $annotation->named('function');
        }

        if ($func && $this->isValidFunction($func)) {
            return $this->attachCallable($testcase, $this->normalizeName($func));
        }
    }

    /**
     * Actually attach the callable to the test case. Eg. call $testcase->{before,after}
     *
     * @since   0.5
     * @param   UnitTestCase $testcase
     * @param   callable $callback
     * @return  void
     */
    abstract protected function attachCallable(UnitTestCase $testcase, callable $callback);

    protected function isUnitTest(TestCase $testcase)
    {
        return $testcase instanceof UnitTestCase;
    }
}
