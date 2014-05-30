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

namespace Demeanor\Annotation;

abstract class AnnotationTestCase
{
    protected function testCaseMock()
    {
        return \Mockery::mock('Demeanor\\Unit\\UnitTestCase')
            ->shouldReceive('getReflectionClass')
            ->andReturn($this->reflectionClass())
            ->getMock();
    }

    protected function testContextMock()
    {
        return \Mockery::mock('Demeanor\\TestContext');
    }

    protected function testResultMock()
    {
        return \Mockery::mock('Demeanor\\TestResult');
    }

    protected function reflectionClass()
    {
        return new \ReflectionClass($this);
    }

    public function cb()
    {
        
    }

    private function privateCb()
    {
        
    }
}
