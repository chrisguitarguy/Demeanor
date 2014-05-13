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

use Demeanor\AbstractTestSuite;

/**
 * A test suite implementation that represents a unit test suite.
 *
 * @since   0.1
 */
class UnitTestSuite extends AbstractTestSuite
{
    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $files = $this->loader->load();
        foreach ($files as $file) {
            include_once $file;
        }

        return $this->compileClasses($files);
    }

    private function compileClasses(array $files)
    {
        $classes = get_declared_classes();
        $testcases = array();
        foreach ($classes as $class) {
            if ('test' !== strtolower(substr($class, -4))) {
                continue;
            }

            $ref = new \ReflectionClass($class);
            if (!in_array($ref->getFilename(), $files)) {
                continue;
            }

            $testcases = array_merge($testcases, $this->compileClass($ref));
        }

        return $testcases;
    }

    private function compileClass(\ReflectionClass $ref)
    {
        $testcases = array();

        if ($ref->isAbstract()) {
            return $testcases;
        }

        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $meth) {
            if ('test' !== strtolower(substr($meth->name, 0, 4))) {
                continue;
            }
            $testcases[] = new UnitTestCase($ref, $meth);
        }

        return $testcases;
    }
}
