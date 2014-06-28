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

namespace Demeanor\Group;

use Demeanor\TestCase;
use Demeanor\Exception\InvalidArgumentException;

/**
 * Provides a container for attaching test cases to groups.
 *
 * Groups are simple strings, normalized to all lower case.
 *
 * @since   0.4
 */
class GroupStorage
{
    /**
     * Container to attach groups to test cases.
     *
     * @since   0.4
     * @var     SplObjectStorage
     */
    private $storage;

    /**
     * Container for the "default instance" of the GroupStorage. Can be fetched
     * with getDefaultInstance
     *
     * @since   0.4
     * @var     GroupStorage
     */
    private static $defaultInstance = null;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public static function getDefaultInstance()
    {
        if (null === self::$defaultInstance) {
            self::$defaultInstance = new self();
        }

        return self::$defaultInstance;
    }

    public function addGroup(TestCase $testcase, $group)
    {
        if (!$this->isKnown($testcase)) {
            $this->storage[$testcase] = new \ArrayObject();
        }
        $this->storage[$testcase][$this->normalizeGroup($group)] = true;
    }

    public function removeGroup(TestCase $testcase, $group)
    {
        if ($this->hasGroup($testcase, $group)) {
            unset($this->storage[$testcase][$this->normalizeGroup($group)]);
        }
    }

    public function hasGroup(TestCase $testcase, $group)
    {
        return $this->isKnown($testcase) && isset($this->storage[$testcase][$this->normalizeGroup($group)]);
    }

    public function clearGroups(TestCase $testcase)
    {
        if ($this->isKnown($testcase)) {
            unset($this->storage[$testcase]);
        }
    }

    private function isKnown(TestCase $testcase)
    {
        return isset($this->storage[$testcase]);
    }

    private function normalizeGroup($group)
    {
        if (!is_string($group)) {
            throw new InvalidArgumentException('$group must be a string');
        }

        return strtolower($group);
    }
}
