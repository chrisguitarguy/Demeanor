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

/**
 * ABC for filters that use other filters.
 *
 * @since   0.4
 */
abstract class AbstractChainFilter implements Filter
{
    private $filters = array();

    public function __construct(array $filters=[])
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
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

    /**
     * Get all the filters registered on the object.
     *
     * @since   0.4
     * @return  Filter[]
     */
    protected function getFilters()
    {
        return $this->filters;
    }
}
