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

use Counterpart\Assert;
use Counterpart\Matchers;

class GroupStorageTest
{
    const GROUP = 'aGroup';

    private $store;
    private $test;

    public function __construct()
    {
        $this->test = \Mockery::mock('Demeanor\\TestCase');
        $this->store = new GroupStorage();
    }

    public function testGetDefaultInstanceAlwaysReturnsTheSameInstance()
    {
        $ins = GroupStorage::getDefaultInstance();
        Assert::assertIdentical($ins, GroupStorage::getDefaultInstance());
    }

    public function testClearingDefaultAllowsForCreationOfNewInstanceInGetDefaultInstance()
    {
        $ins = GroupStorage::getDefaultInstance();
        Assert::assertIdentical($ins, GroupStorage::getDefaultInstance());
        GroupStorage::clearDefaultInstance();
        Assert::assertThat(
            Matchers::logicalNot(Matchers::isIdentical($ins)),
            GroupStorage::getDefaultInstance(),
            'should create a new instance after clearDefaultInstance is called'
        );
    }

    public function testHasAndRemoveRespondAppropriately()
    {
        Assert::assertFalse($this->store->hasGroup($this->test, self::GROUP));
        $this->store->addGroup($this->test, self::GROUP);
        Assert::assertTrue($this->store->hasGroup($this->test, self::GROUP));
        $this->store->removeGroup($this->test, self::GROUP);
        Assert::assertFalse($this->store->hasGroup($this->test, self::GROUP));
    }

    public function testClearGroupsRemovesAllGroupsFromATestCase()
    {
        $this->store->addGroup($this->test, self::GROUP);
        Assert::assertTrue($this->store->hasGroup($this->test, self::GROUP));
        $this->store->clearGroups($this->test);
        Assert::assertFalse($this->store->hasGroup($this->test, self::GROUP));
    }

    /**
     * @Expect("Demeanor\Exception\DemeanorException")
     */
    public function testNonStringGroupNameThrowsException()
    {
        $this->store->addGroup($this->test, ['not', 'a', 'string']);
    }
}