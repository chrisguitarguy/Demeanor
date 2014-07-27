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

namespace Demeanor\Filter;

use Demeanor\TestCase;
use Demeanor\Group\GroupStorage;
use Demeanor\Group\StorageLocator;

/**
 * Check to see if a test is in a group.
 *
 * @since   0.4
 */
class GroupFilter implements Filter
{
    private $groupName;
    private $groupStorage;

    /**
     * Set up the group name and, optionally, the group storage.
     *
     * @since   0.4
     * @param   string $groupName The group name to filter on
     * @param   GroupStorage $groupStorage If none is provided the default instance
     *          will be fetched.
     * @return  void
     */
    public function __construct($groupName, GroupStorage $groupStorage=null)
    {
        $this->groupName = $groupName;
        $this->groupStorage = $groupStorage ?: StorageLocator::get();
    }

    /**
     * {@inheritdoc}
     */
    public function canRun(TestCase $testcase)
    {
        return $this->groupStorage->hasGroup($testcase, $this->groupName);
    }
}
