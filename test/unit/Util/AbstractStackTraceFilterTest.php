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

namespace Demeanor\Util;

use Counterpart\Assert;

class AbstractStackTraceFilterTest
{
    private $filter;

    public function __construct()
    {
        $this->filter = \Mockery::mock('Demeanor\\Util\\AbstractStackTraceFilter[doFilter]');
        $this->filter->shouldAllowMockingProtectedMethods();
    }

    /**
     * @Expect("Demeanor\Exception\DemeanorException")
     */
    public function testFilteringANonExceptionOrArrayThrowsException()
    {
        $this->shouldNotFilter();
        $this->filter->filterTrace('not an array');
    }

    /**
     * @Expect("Demeanor\Exception\DemeanorException")
     */
    public function testTraceAsStringThrowsWhenGivenNonArrayOrException()
    {
        $this->shouldNotFilter();
        $this->filter->traceAsString('not an array');
    }

    public function testFilterTraceWithArrayProxiesToDoFilter()
    {
        $this->shouldFilter();
        Assert::assertType('array', $this->filter->filterTrace([
            ['file' => 'one']
        ]));
    }

    public function testFilterTraceWithExceptionProxiesToDoFilter()
    {
        $this->shouldFilter();
        Assert::assertType('array', $this->filter->filterTrace(new \Exception('broken')));
    }

    public function testTraceAsStringWithArrayReturnsString()
    {
        $this->shouldFilter();
        Assert::assertType('string', $this->filter->traceAsString([
            ['function' => 'is_array'],
            ['file' => 'one']
        ]));
    }

    public function testTraceAsStringWithExceptionReturnsString()
    {
        $this->shouldFilter();
        Assert::assertType('string', $this->filter->traceAsString(new \Exception('broken')));
    }

    private function shouldFilter()
    {
        $this->filter->shouldReceive('doFilter')
            ->atLeast(1)
            ->andReturnUsing(function ($f) {
                return $f;
            });
    }

    private function shouldNotFilter()
    {
        $this->filter->shouldReceive('doFilter')
            ->never();
    }
}
