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

use Demeanor\TestSuite;
use Demeanor\Loader\Loader;

/**
 * A test suite implementation that represents a unit test suite.
 *
 * @since   0.1
 */
class UnitTestSuite implements TestSuite
{
    private $loader;
    private $name;
    private $bootstrap;

    public function __construct($name, Loader $loader, array $bootstrap=array())
    {
        $this->name = $name;
        $this->loader = $loader;
        $this->bootstrap = $bootstrap;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $currentClasses = get_declared_classes();
        $files = $this->loader->load();
        foreach ($files as $file) {
            include_once $file;
        }

        $newClasses = array_diff(get_declared_classes(), $currentClasses);

        return $this->compileClasses($newClasses);
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap()
    {
        foreach ($this->bootstrap as $bootstrap) {
            require_once $bootstrap;
        }
    }

    private function compileClasses(array $classes)
    {
        $testcases = array();
        foreach ($classes as $class) {
            if ('test' !== strtolower(substr($class, -4))) {
                continue;
            }

            $testcases = array_merge($testcases, $this->compileClass($class));
        }

        return $testcases;
    }

    private function compileClass($class)
    {
        $testcases = array();
        $ref = new \ReflectionClass($class);
        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $meth) {
            if ('test' !== strtolower(substr($meth->name, 0, 4))) {
                continue;
            }
            $testcases[] = new UnitTestCase($ref, $meth);
        }

        return $testcases;
    }
}
