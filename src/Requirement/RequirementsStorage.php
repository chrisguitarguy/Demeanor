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

namespace Demeanor\Requirement;

use Demeanor\TestCase;

/**
 * An object that associates requirements objects with test cases.
 *
 * @since   0.5
 */
class RequirementsStorage
{
    /**
     * The underlying SplObjectStorage
     *
     * @since   0.5
     * @var     SplObjectStorage
     */
    private $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public function get(TestCase $testcase)
    {
        if (!$this->has($testcase)) {
            $this->set($testcase, new Requirements());
        }

        return $this->storage[$testcase];
    }

    public function set(TestCase $testcase, Requirements $reqs)
    {
        $this->storage[$testcase] = $reqs;
    }

    public function has(TestCase $testcase)
    {
        return isset($this->storage[$testcase]);
    }

    public function remove(TestCase $testcase)
    {
        if ($this->has($testcase)) {
            $this->storage->detach($testcase);
        }
    }
}
