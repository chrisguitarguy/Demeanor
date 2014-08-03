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
 * An ABC for all the annotation used in this extenion. Annotation know how to
 * attach themselves to test suites.
 *
 * @since   0.1
 */
abstract class AbstractAnnotation implements Annotation
{
    protected $positional = array();
    protected $args = array();

    /**
     * Costructor. Set up the arguments from the annotation parser.
     *
     * @since   0.1
     * @param   array $args
     */
    public function __construct(array $positional, array $args)
    {
        $this->positional = $positional;
        $this->args = $args;
    }

    /**
     * {@inheritdoc}
     */
    public function positional($index)
    {
        return isset($this->positional[$index]) ? $this->positional[$index] : self::ARGUMENT_NOT_FOUND;
    }

    /**
     * {@inheritdoc}
     */
    public function allPositional()
    {
        return new \ArrayIterator($this->positional);
    }

    /**
     * {@inheritdoc}
     */
    public function named($name)
    {
        return isset($this->args[$name]) ? $this->args[$name] : self::ARGUMENT_NOT_FOUND;
    }

    /**
     * {@inheritdoc}
     */
    public function allNamed()
    {
        return new \ArrayIterator($this->args);
    }
}
