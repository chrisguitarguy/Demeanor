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

class MethodValidatorTraitTest
{
    use \Counterpart\Assert;
    use MethodValidatorTrait;

    public function testIsValidMethodFailsWhenTestCaseDoesNotSupportMethods()
    {
        $tc = \Mockery::mock('Demeanor\\TestCase');

        $this->assertFalse($this->isValidMethod(__FUNCTION__, $tc));
    }

    public function testIsValidMethodSucceedsWhenTestCaseHasExpectedMethod()
    {
        $tc = $this->testcase();

        $this->assertTrue($this->isValidMethod('validMethod', $tc));
    }

    public function testIsValidMethodFailsWhenMethodDoesNotExist()
    {
        $tc = $this->testcase();

        $this->assertFalse($this->isValidMethod('doesNotExist', $tc));
    }

    public function testIsValidMethodFailsWhenMethodIsNotPublic()
    {
        $tc = $this->testcase();

        $this->assertFalse($this->isValidMethod('testcase', $tc));
    }

    public function testIsValidStaticMethodFailsWhenMethodIsInvalid()
    {
        $tc = $this->testcase();

        $this->assertFalse($this->isValidStaticMethod('doesNotExist', $tc));
    }

    public function testIsValidStaticMethodFailsWhenMethodIsNotStatic()
    {
        $tc = $this->testcase();

        $this->assertFalse($this->isValidStaticMethod('validMethod', $tc));
    }

    public function testIsValidStaticMethodSucceedsWhenMethodIsStatic()
    {
        $tc = $this->testcase();

        $this->assertTrue($this->isValidStaticMethod('validStaticMethod', $tc));
    }

    public function validMethod()
    {
        // noop
    }

    public static function validStaticMethod()
    {
        
    }

    private function testcase()
    {
        $tc = \Mockery::mock('Demeanor\\Unit\\UnitTestCase');
        $tc->shouldReceive('getReflectionClass')
            ->andReturn(new \ReflectionClass($this));

        return $tc;
    }
}
