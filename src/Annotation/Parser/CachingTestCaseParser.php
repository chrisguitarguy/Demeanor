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

namespace Demeanor\Annotation\Parser;

use Demeanor\TestCase;

class CachingTestCaseParser implements TestCaseParser
{
    /**
     * @since   0.5
     * @var     TestCaseParser
     */
    private $realParser;

    /**
     * @since   0.5
     * @var     SplObjectStorage
     */
    private $cache;

    /**
     * Optionally set up the real parser.
     *
     * @since   0.5
     * @param   TestCaseParser|null $realParser
     * @return  void
     */
    public function __construct(TestCaseParser $realParser)
    {
        $this->realParser = $realParser;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function annotationsFor(TestCase $testcase)
    {
        if (!isset($this->cache[$testcase])) {
            $this->cache[$testcase] = $this->realParser->annotationsFor($testcase);
        }

        return $this->cache[$testcase];
    }
}
