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

/**
 * Uses a collection of other filters to see if a test case can run.
 *
 * @since   0.2
 */
class ChainFilter implements Filter
{
    private $filters = array();

    public function __construct(array $filters=[])
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * {@inheritdoc}
     * Will only allow a test case through if no filters are in the chain or at
     * least one filter is met.
     */
    public function canRun(TestCase $test)
    {
        if (!$this->filters) {
            return true;
        }

        foreach ($this->filters as $filter) {
            if (!$filter->canRun($test)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Add a new filter to the chain.
     *
     * @since   0.2
     * @param   Filter $filter
     * @return  void
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }
}
