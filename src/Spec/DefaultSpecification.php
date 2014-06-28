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

use Demeanor\Group\GroupStorage;

class DefaultSpecification implements Specification
{
    private $collection;
    private $before = array();
    private $after = array();
    private $groups = array();
    private $description = null;

    public function __construct(
        TestCaseCollection $collection,
        $desc,
        \Closure $spec,
        array $before=array(),
        array $after=array(),
        array $groups=array()
    ) {
        $this->collection = $collection;
        $this->description = $desc;
        $this->before = $before;
        $this->after = $after;
        $this->groups = $groups;

        $spec = $spec->bindTo($this);
        $spec();
    }

    /**
     * {@inheritdoc}
     */
    public function describe($description, \Closure $spec=null)
    {
        if (null === $spec) {
            $this->description = $description;
            return;
        }

        new self(
            $this->collection,
            $this->description.$description,
            $spec,
            $this->before,
            $this->after,
            $this->groups
        );
    }

    /**
     * {@inheritdoc}
     */
    public function before(\Closure $before)
    {
        $this->before[] = $before;
    }

    /**
     * {@inheritdoc}
     */
    public function after(\Closure $after)
    {
        $this->after[] = $after;
    }

    /**
     * {@inheritdoc}
     */
    public function it($description, \Closure $it)
    {
        $testcase = new SpecTestCase(
            sprintf('[%s] %s', $this->description, $description),
            $it,
            $this->before,
            $this->after
        );
        foreach ($this->groups as $group) {
            GroupStorage::getDefaultInstance()->addGroup($testcase, $group);
        }

        $this->collection->put($testcase);
    }

    /**
     * {@inheritdoc}
     */
    public function group($group)
    {
        $this->groups[] = (string)$group;
    }
}
