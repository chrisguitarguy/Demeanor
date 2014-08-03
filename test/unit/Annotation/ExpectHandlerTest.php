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

class ExpectHandlerTest extends CallbackTestCase
{
    use \Counterpart\Assert;

    private $handler;

    public function __construct()
    {
        $this->handler = new ExpectHandler();
    }

    public function testNoExceptionArgumentDoesNothing()
    {
        $tc = $this->testcase();
        $this->willNotThrow($tc);
        $expect = new Expect([], []);

        $this->handler->onRun($expect, $tc, $this->result());
    }

    public function testExceptionInPositionalMarksTestAsThrowing()
    {
        $tc = $this->testcase();
        $this->willThrow($tc);
        $expect = new Expect(['InvalidArgumentException'], []);

        $this->handler->onRun($expect, $tc, $this->result());
    }

    public function testExceptionInNamedMarksTestAsThrowing()
    {
        $tc = $this->testcase();
        $this->willThrow($tc);
        $expect = new Expect([], ['exception' => 'InvalidArgumentException']);

        $this->handler->onRun($expect, $tc, $this->result());
    }

    public function testInvalidExceptionClassErrorsResult()
    {
        $tc = $this->testcase();
        $this->willNotThrow($tc);
        $expect = new Expect(['ThisClassDoesNotExist'], []);
        $result = $this->result();

        $this->handler->onRun($expect, $tc, $result);

        $this->assertTrue($result->errored());
    }

    private function willThrow($tc)
    {
        $tc->shouldReceive('willThrow')
            ->atLeast(1);
    }

    private function willNotThrow($tc)
    {
        $tc->shouldReceive('willThrow')
            ->never();
    }
}
