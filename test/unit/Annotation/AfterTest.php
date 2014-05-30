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

/**
 * TODO whenever data providers happen this and BeforeTest can be combined
 */
class AfterTest extends AnnotationTestCase
{
    public function testNoValidCallablesDoesNotAttachBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->never();
        $annot = new After([], []);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testMethodWithPrivateMethodCallbackDoesNotAddBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->never();
        $annot = new After([], ['method' => 'privateCb']);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testMethodThatDoesNotExistDoesNotAddBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->never();
        $annot = new After([], ['method' => 'methodDoesNotExist']);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testValidMethodAddsBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('getTestObject')
            ->once()
            ->andReturn($this);
        $testcase->shouldReceive('after')
            ->once()
            ->with([$this, 'cb']);
        $annot = new After([], ['method' => 'cb']);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testAfterWithPositionalArgumentTreatsItLikeMethodCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('getTestObject')
            ->once()
            ->andReturn($this);
        $testcase->shouldReceive('after')
            ->once()
            ->with([$this, 'cb']);
        $annot = new After(['cb'], []);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testPositionalArgumentOfAMethodThatDoesNotExistDoesNotAddCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->never();
        $annot = new After(['methodDoesNotExist'], []);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testFunctionThatDoesNotExistDoesNotAddBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->never();
        $annot = new After([], ['function' => 'function_does_not_exist']);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }

    public function testValidFuncionAddsBeforeCallback()
    {
        $testcase = $this->testCaseMock();
        $testcase->shouldReceive('after')
            ->once()
            ->with('is_array');
        $annot = new After([], ['function' => 'is_array']);

        $annot->attachRun($testcase, $this->testContextMock(), $this->testResultMock());
    }
}