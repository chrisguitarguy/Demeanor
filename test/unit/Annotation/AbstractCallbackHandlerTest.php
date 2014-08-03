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

use Demeanor\TestCaseStub;
use Demeanor\Unit\UnitTestCase;

class _CallbackHandlerSpy extends AbstractCallbackHandler
{
    use \Counterpart\Assert;
    private $calls = 0;
    private $callback = null;

    public function assertCalledWith($type)
    {
        $this->assertType($type, $this->callback, 'Callback Handler Was Not Called With Expected Type');
    }

    public function assertNotCalled()
    {
        $this->assertEquals(0, $this->calls, 'attachCallable should not have been called');
    }

    protected function attachCallable(UnitTestCase $testcase, callable $callback)
    {
        $this->callback = $callback;
        $this->calls++;
    }
}

class AbstractCallbackHandlerTest extends CallbackTestCase
{
    use \Counterpart\Assert;

    private $handler;

    public function __construct()
    {
        $this->handler = new _CallbackHandlerSpy();
    }

    public function testNotUnitTestTestCaseDoesNothingOnRun()
    {
        $this->handler->onRun(new Before([], []), new TestCaseStub(), $this->result());
        $this->handler->assertNotCalled();
    }

    public function testValidMethodInPositionalArgumentsAttachesCallback()
    {
        $tc = $this->testcase();
        $before = new Before(['validMethod'], []);

        $this->handler->onRun($before, $tc, $this->result());

        $this->handler->assertCalledWith('array');
    }

    public function testValidMethodInNamedArgumentsAttachesCallback()
    {
        $tc = $this->testcase();
        $before = new Before([], ['method' => 'validMethod']);

        $this->handler->onRun($before, $tc, $this->result());

        $this->handler->assertCalledWith('array');
    }

    public function testValidFunctionInPositionalArgumentsAttachesCallback()
    {
        $tc = $this->testcase();
        $before = new Before(['is_array'], []);

        $this->handler->onRun($before, $tc, $this->result());

        $this->handler->assertCalledWith('string');
    }

    public function testValidFunctionInNamedArgumentsAttachesCallback()
    {
        $tc = $this->testcase();
        $before = new Before([], ['function' => 'is_array']);

        $this->handler->onRun($before, $tc, $this->result());

        $this->handler->assertCalledWith('string');
    }
}
