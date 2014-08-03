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
 * Provides utilities for validating method names in annotations.
 *
 * @since   0.5
 */
trait MethodValidatorTrait
{
    protected function isValidMethod($method, TestCase $testcase)
    {
        if (!$this->testcaseSupportsMethods($testcase)) {
            return false;
        }

        try {
            $ref = $testcase->getReflectionClass()->getMethod($method);
        } catch (\ReflectionException $e) {
            return false;
        }

        return $ref->isPublic();
    }

    protected function isValidStaticMethod($method, TestCase $testcase)
    {
        if (!$this->isValidMethod($method, $testcase)) {
            return false;
        }

        $ref = $testcase->getReflectionClass()->getMethod($method);

        return $ref->isStatic();
    }

    /**
     * Check to see if a test case supports methods.
     *
     * @since   0.5
     * @param   TestCase $testcase
     * @return  boolean
     */
    protected function testcaseSupportsMethods(TestCase $testcase)
    {
        return method_exists($testcase, 'getReflectionClass');
    }
}
