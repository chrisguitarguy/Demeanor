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

use Demeanor\TestCase;
use Demeanor\DefaultTestResult;
use Demeanor\DefaultTestContext;
use Demeanor\Exception\TestFailed;
use Demeanor\Exception\TestSkipped;

class UnitTestCase implements TestCase
{
    private $refClass;
    private $refMethod;
    private $name = null;

    public function __construct(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $this->refClass = $class;
        $this->refMethod = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (null !== $this->name) {
            return $this->name;
        }

        $this->name = $this->prettifyName();

        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $object = $this->createObject();
        $result = new DefaultTestResult();
        $context = new DefaultTestContext($result);
        try {
            $this->refMethod->invoke($object, $context);
        } catch (TestFailed $e) {
            $result->fail();
        } catch (TestSkipped $e) {
            $result->skip();
        } catch (\Exception $e) {
            $result->addMessage('error', $e->getMessage());
            $result->error();
        }

        return $result;
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
     * @since   0.1
     * @return  object
     */
    private function createObject()
    {
        return $this->refClass->newInstanceArgs([]);
    }
}
