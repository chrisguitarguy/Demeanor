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

class UnitTestCase implements TestCase
{
    private $class;
    private $method;
    private $name = null;

    public function __construct(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $this->class = $class;
        $this->method = $method;
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
        
    }

    /**
     * @see     http://stackoverflow.com/questions/8577300/explode-a-string-on-upper-case-characters
     */
    private function prettifyName()
    {
        return implode(' ', preg_split(
            '/(?=[A-Z])/',
            substr($this->method->name, 4),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}
