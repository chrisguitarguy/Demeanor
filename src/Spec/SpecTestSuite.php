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

use Demeanor\AbstractTestSuite;

/**
 * A test suite implementation that represents a spec test suite
 *
 * @since   0.1
 */
class SpecTestSuite extends AbstractTestSuite
{
    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $collection = new TestCaseCollection();

        $files = $this->loader->load();
        foreach ($files as $file) {
            new DefaultSpecification(
                $collection,
                $this->filenameDescription($file),
                function () use ($file) {
                    include_once $file;
                }
            );
        }

        return $collection->all();
    }

    private function filenameDescription($filename)
    {
        $filename = basename($filename, '.php');
        $parts = explode('.', $filename, 2);
        $name = array_shift($parts);
        return str_replace('_', ' ', $name);
    }
}
