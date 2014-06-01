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

use Demeanor\Event\Emitter;
use Demeanor\Event\TestCaseEvent;
use Demeanor\Event\TestSuiteEvent;
use Demeanor\Loader\Loader;
use Demeanor\Output\OutputWriter;

/**
 * ABC for test suites.
 *
 * @since   0.1
 */
abstract class AbstractTestSuite implements TestSuite
{
    protected $loader;
    protected $name;
    protected $bootstrap;

    public function __construct($name, Loader $loader, array $bootstrap=array())
    {
        $this->name = $name;
        $this->loader = $loader;
        $this->bootstrap = $bootstrap;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap()
    {
        foreach ($this->bootstrap as $bootstrap) {
            require_once $bootstrap;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Emitter $emitter, OutputWriter $output)
    {
        $this->bootstrap();
        $tests = $this->load();

        $emitter->emit(Events::BEFORE_TESTSUITE, new TestSuiteEvent($this));

        $results = new DefaultResultSet();
        foreach ($tests as $test) {
            $emitter->emit(Events::SETUP_TESTCASE, new TestCaseEvent($test));

            if ($test->hasProvider()) {
                foreach ($test->getProvider() as $argsName => $testArgs) {
                    if (!is_array($testArgs)) {
                        $testArgs = [$testArgs];
                    }
                    $_test = clone $test;
                    $_test->addDescriptor('Data Set '.(is_int($argsName) ? "#{$argsName}" : $argsName));
                    $results->add($test, $this->runTestCase($_test, $emitter, $output, $testArgs));
                }
            } else {
                $results->add($test, $this->runTestCase($test, $emitter, $output));
            }

            $emitter->emit(Events::TEARDOWN_TESTCASE, new TestCaseEvent($test));
        }

        $emitter->emit(Events::AFTER_TESTSUITE, new TestSuiteEVent($this, $results));

        return $results;
    }

    protected function runTestCase(TestCase $test, Emitter $emitter, OutputWriter $output, array $testArgs=[])
    {
        $result = $test->run($emitter, $testArgs);

        return $result;
    }
}
