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

namespace Demeanor\Extension\Annotation;

class ExpectTest extends AnnotationTestCase
{
    public function testNoExceptionInArgumentsDoesNotSetExpectedException()
    {
        $tc = $this->testCaseMock();
        $tc->shouldReceive('setExpectedException')
            ->never();
        $annot = new Expect([]);

        $annot->attach($tc, $this->testContextMock(), $this->testResultMock());
    }

    public function testExceptionThatDoesNotExistErrorsTestAndLogsError()
    {
        $tc = $this->testCaseMock();
        $tc->shouldReceive('setExpectedException')
            ->never();
        $tr = $this->testResultMock();
        $tr->shouldReceive('error')
            ->once();
        $tr->shouldReceive('addMessage')
            ->once();
        $annot = new Expect(['exception' => 'IsNotAValidException']);

        $annot->attach($tc, $this->testContextMock(), $tr);
    }

    public function testValidExceptionSetsExpectedExceptionOnTestCase()
    {
        $tc = $this->testCaseMock();
        $tc->shouldReceive('setExpectedException')
            ->once()
            ->with('Exception');
        $annot = new Expect(['exception' => 'Exception']);

        $annot->attach($tc, $this->testContextMock(), $this->testResultMock());
    }
}
