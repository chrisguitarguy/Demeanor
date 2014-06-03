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

namespace Demeanor\Finder;

use Counterpart\Assert;

class ExcludingFinderTest
{
    public function testAllReturnsOnlyFilesThatWereNotBlacklisted()
    {
        $whitelist = $this->finderReturning([__DIR__, __FILE__]);
        $blacklist = $this->finderReturning([__DIR__]);
        $finder = new ExcludingFinder($whitelist, $blacklist);

        $found = $finder->all();

        // can't rely on array equality here due to the array_diff usage in ExcludingFinder
        Assert::assertCount(1, $found);
        Assert::assertContains(__FILE__, $found);
    }

    private function finderReturning(array $files)
    {
        $f = \Mockery::mock('Demeanor\\Finder\\Finder');
        $f->shouldReceive('all')
            ->atLeast(1)
            ->andReturn($files);

        return $f;
    }
}
