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

namespace Demeanor;

use Counterpart\Exception\AssertionFailed;
use Demeanor\Event\Emitter;
use Demeanor\Event\TestRunEvent;
use Demeanor\Exception\TestFailed;
use Demeanor\Exception\TestSkipped;
use Demeanor\Exception\InvalidArgumentException;

abstract class AbstractTestCase implements TestCase
{
    protected $before = array();
    protected $after = array();
    protected $expectedException = null;
    protected $caughtException = null;
    protected $dataProvider = null;

    /**
     * {@inheritdoc}
     */
    public function run(Emitter $emitter)
    {
        $result = new DefaultTestResult();
        $context = new DefaultTestContext($this, $result);

        $emitter->emit(Events::BEFORE_TESTCASE, new TestRunEvent($this, $context, $result));

        // the listeners on `BEFORE_TESTCASE` might mark the test as something
        // other than successful. If that's the case, we need not to continue
        // the test, just return the result and be done.
        if (!$result->successful()) {
            return $result;
        }

        $argName = null;
        try {
            if ($this->hasProvider()) {
                foreach ($this->dataProvider as $argName => $testArgs) {
                    if (!is_array($testArgs)) {
                        $testArgs = [$testArgs];
                    }
                    array_unshift($testArgs, $context);
                    $this->runTest($emitter, $context, $result, $testArgs);
                }
            } else {
                $this->runTest($emitter, $context, $result, [$context]);
            }
        } catch (TestFailed $e) {
            $result->fail();
        } catch (TestSkipped $e) {
            $result->skip();
        } catch (AssertionFailed $e) {
            $result->fail();
            $this->addAssertMessage($result, $e);
        } catch (\Exception $e) {
            $this->caughtException($e);
            if (!$this->isExpected($e)) {
                $result->addMessage('error', $e->getMessage());
                $result->error();
                $emitter->emit(Events::EXCEPTION_TESTCASE, new TestRunEvent($this, $context, $result));
            }
        }

        if (!$this->caughtExpectedException()) {
            $result->fail();
            $result->addMessage('fail', sprintf(
                'Expected exception of class %s, got %s',
                $this->expectedException,
                is_object($this->caughtException) ? get_class($this->caughtException) : gettype($this->caughtException)
            ));
        }

        $emitter->emit(Events::AFTER_TESTCASE, new TestRunEvent($this, $context, $result));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function willThrow($exceptionClass)
    {
        $this->expectedException = $exceptionClass;
    }

    /**
     * {@inheritdoc}
     */
    public function withProvider($provider)
    {
        if (!is_array($provider) && !$provider instanceof \Traversable) {
            throw new InvalidArgumentException('Data providers must be arrays or Traversables');
        }

        $this->dataProvider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProvider()
    {
        return !empty($this->dataProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function before(callable $cb)
    {
        $this->before[] = $cb;
    }

    /**
     * {@inheritdoc}
     */
    public function after(callable $cb)
    {
        $this->after[] = $cb;
    }

    /**
     * Do the actual test run.
     *
     * @since   0.1
     * @param   TestContext $ctx
     * @param   TestResult $result
     * @return  void
     */
    abstract protected function doRun(array $testArgs);

    /**
     * Invoke the do the before/after callbacks and call the `doRun` method with
     * $testArgs
     *
     * @since   0.1
     * @param   Emitter $emitter
     * @param   array $testArgs
     * @return  void
     */
    protected function runTest(Emitter $emitter, TestContext $context, TestResult $result, array $testArgs)
    {
        $this->doBeforeCallbacks($context);
        $emitter->emit(Events::BEFORERUN_TESTCASE, new TestRunEvent($this, $context, $result));
        $this->doRun($testArgs);
        $emitter->emit(Events::AFTERRUN_TESTCASE, new TestRunEvent($this, $context, $result));
        $this->doAfterCallbacks($context); // TODO figure out how to make these run every time
    }

    /**
     * Check whether an exception was expected or not.
     *
     * @since   0.1
     * @param   \Exception $e
     * @return  boolean
     */
    protected function isExpected(\Exception $e)
    {
        if (null === $this->expectedException) {
            return false;
        }

        return $e instanceof $this->expectedException;
    }

    /**
     * Set the exception that was caught during test execution.
     *
     * @since   0.1
     * @param   \Exception $e
     * @return  void
     */
    protected function caughtException(\Exception $e)
    {
        $this->caughtException = $e;
    }

    /**
     * Check whether or not we caught the expected exception.
     *
     * @since   0.1
     * @return  boolean
     */
    protected function caughtExpectedException()
    {
        if (null === $this->expectedException) {
            return true;
        }

        return $this->caughtException && $this->isExpected($this->caughtException);
    }

    protected function addAssertMessage(TestResult $result, AssertionFailed $e)
    {
        $trace = $e->getTrace();
        array_shift($trace); // first item is always Counterpart\Assert::assertThat
        $where = array_shift($trace);
        if (!$where) {
            return $result->addMessage('fail', $e->getMessage());
        }

        $file = isset($where['file']) ? $where['file'] : null;
        $line = isset($where['line']) ? $where['line'] : null;

        $result->addMessage('fail', sprintf(
            '%s in %s, line %s',
            $e->getMessage(),
            $file ?: 'UNKNOWN',
            $line ?: 'UNKNOWN'
        ));
    }

    protected function doBeforeCallbacks(TestContext $ctx)
    {
        foreach ($this->before as $cb) {
            $this->doCallback($cb, $ctx);
        }
    }

    protected function doAfterCallbacks(TestContext $ctx)
    {
        foreach ($this->after as $cb) {
            $this->doCallback($cb, $ctx);
        }
    }

    protected function doCallback(callable $cb, TestContext $ctx)
    {
        if ($cb instanceof \Closure) {
            $cb = $cb->bindTo(null);
        }

        call_user_func($cb, $ctx);
    }
}
