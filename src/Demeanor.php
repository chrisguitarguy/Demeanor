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
use Demeanor\Event\DefaultEmitter;
use Demeanor\Event\TestCaseEvent;
use Demeanor\Subscriber\MockerySubscriber;
use Demeanor\Subscriber\AnnotationSubscriber;
use Demeanor\Subscriber\RequirementSubscriber;
use Demeanor\Subscriber\ExceptionSubscriber;
use Demeanor\Subscriber\ResultWritingSubscriber;
use Demeanor\Subscriber\FilterSubscriber;
use Demeanor\Config\Configuration;
use Demeanor\Output\OutputWriter;
use Demeanor\Exception\ConfigurationException;

/**
 * The main application class.
 *
 * @since   0.1
 */
final class Demeanor
{
    const VERSION   = '0.2';
    const NAME      = 'Demeanor';

    const EXIT_SUCCESS      = 0;
    const EXIT_TESTERROR    = 1;
    const EXIT_ERROR        = 2;

    private $outputWriter;
    private $emitter;
    private $config;

    public function __construct(OutputWriter $writer, Configuration $config, Emitter $emitter=null)
    {
        $this->outputWriter = $writer;
        $this->config = $config;
        $this->emitter = $emitter ?: new DefaultEmitter();
    }

    public function run()
    {
        try {
            $this->config->initialize();
        } catch (ConfigurationException $e) {
            $this->outputWriter->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return self::EXIT_ERROR;
        }

        $this->addEventSubscribers();

        set_error_handler([$this, 'errorException']);

        $hasErrors = false;
        $results = [];
        foreach ($this->loadTestSuites() as $name => $testsuite) {
            if (!$this->config->suiteCanRun($name)) {
                continue;
            }

            $results[$name] = $testsuite->run($this->emitter, $this->outputWriter);
            $hasErrors = $hasErrors || !$results[$name]->successful();
        }

        restore_error_handler();

        return $hasErrors ? self::EXIT_TESTERROR : self::EXIT_SUCCESS;
    }

    public function errorException($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    private function loadTestSuites()
    {
        $factory = new TestSuiteFactory();
        $suites = array();
        foreach ($this->config->getTestSuites() as $name => $suiteConfig) {
            $suites[$name] = $factory->create($name, $suiteConfig);
        }

        return $suites;
    }

    private function addEventSubscribers()
    {
        $subscribers = array_merge([
            new MockerySubscriber(),
            new AnnotationSubscriber(),
            new RequirementSubscriber(),
            new ExceptionSubscriber(),
            new ResultWritingSubscriber($this->outputWriter),
            new FilterSubscriber($this->config->getFilters()),
        ], $this->config->getEventSubscribers());

        foreach ($subscribers as $sub) {
            $this->emitter->addSubscriber($sub);
        }
    }
}
