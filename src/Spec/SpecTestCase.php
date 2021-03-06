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

use Demeanor\AbstractTestCase;

/**
 * Represents a specification test case.
 *
 * @since   0.1
 */
class SpecTestCase extends AbstractTestCase
{
    private $name;
    private $testClosure;
    private $reflection = null;

    public function __construct($name, \Closure $testClosure, array $before, array $after)
    {
        $this->name = $name;
        $this->testClosure = $testClosure;
        foreach ($before as $cb) {
            $this->before($cb);
        }
        foreach ($after as $cb) {
            $this->after($cb);
        }
    }

    /**
     * Get the reflection function for the test closure itself.
     *
     * @since   0.2
     * @return  ReflectionFunction
     */
    public function getReflectionFunction()
    {
        if (null === $this->reflection) {
            $this->reflection = new \ReflectionFunction($this->testClosure);
        }
        return $this->reflection;
    }

    /**
     * {@inheritdoc}
     */
    public function filename()
    {
        return $this->getReflectionFunction()->getFilename();
    }

    /**
     * {@inheritdoc}
     */
    public function lineno()
    {
        return $this->getReflectionFunction()->getStartLine();
    }

    /**
     * {@inheritdoc}
     */
    protected function doRun(array $testArgs)
    {
        call_user_func_array($this->testClosure->bindTo(null), $testArgs);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateName()
    {
        return $this->name;
    }
}
