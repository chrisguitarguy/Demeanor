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

namespace Demeanor\Unit;

use Demeanor\AbstractTestCase;
use Demeanor\TestResult;
use Demeanor\TestContext;

class UnitTestCase extends AbstractTestCase
{
    private $refClass;
    private $refMethod;
    private $name = null;
    private $testObject = null;

    public function __construct(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $this->refClass = $class;
        $this->refMethod = $method;
    }

    /**
     * Set the `testObject` to null so cloned instances don't share the same
     * instance.
     *
     * @since   0.1
     * @return  void
     */
    public function __clone()
    {
        $this->testObject = null;
        $this->name = null;
    }

    /**
     * Get the object that will be used for the unit test. This will always
     * return the same instance.
     *
     * @since   0.1
     * @return  object
     */
    public function getTestObject()
    {
        if (null === $this->testObject) {
            $this->testObject = $this->createObject();
        }

        return $this->testObject;
    }

    /**
     * Get the reflection class for the test object.
     *
     * @since   0.1
     * @return  ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->refClass;
    }

    /**
     * Get the reflection method for the test case.
     *
     * @since   0.1
     * @return  ReflectionMethod
     */
    public function getReflectionMethod()
    {
        return $this->refMethod;
    }

    /**
     * {@inheritdoc}
     */
    protected function doRun(array $testArgs)
    {
        $this->getReflectionMethod()->invokeArgs($this->getTestObject(), $testArgs);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateName()
    {
        if (null !== $this->name) {
            return $this->name;
        }

        $this->name = $this->prettifyName();

        return $this->name;
    }

    /**
     * @see     http://stackoverflow.com/questions/8577300/explode-a-string-on-upper-case-characters
     */
    private function prettifyName()
    {
        $testName = implode(' ', preg_split(
            '/(?=[A-Z])/',
            substr($this->refMethod->name, 4),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));

        return sprintf('[%s] %s', $this->refClass->name, $testName);
    }

    /**
     * Creates the class in which the test method resides.
     *
     * TODO allow for factory methods.
     *
     * @since   0.1
     * @return  object
     */
    private function createObject()
    {
        return $this->getReflectionClass()->newInstanceArgs([]);
    }
}
