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

class FirstExternalStackTraceFilterTest
{
    /**
     * @Expect("Demeanor\Exception\DemeanorException")
     */
    public function testCreationWithInvalidClassNameThrows()
    {
        new FirstExternalStackTraceFilter(__NAMESPACE__.'\\ThisClassDoesNotExist');
    }

    public function testFilterStackReturnsOnlyOnceFrame()
    {
        $except = $this->createException();
        $result = $this->createFilter()->filterTrace($except);

        Assert::assertType('array', $result);
        Assert::assertCount(1, $result);
        Assert::assertEquals(__FILE__, $result[0]['file']);
    }

    public function testTraceToStringReturnsLineContainingExpectedFiledName()
    {
        $except = $this->createException();
        $result = $this->createFilter()->traceToString($except);

        Assert::assertType('string', $result);
        Assert::assertStringContains(__FILE__, $result);
    }

    private function createFilter()
    {
        return new FirstExternalStackTraceFilter('Counterpart\\Assert');
    }

    private function createException()
    {
        try {
            Assert::assertFalse(true);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
