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

namespace Demeanor\Output;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Demeanor\TestCase;
use Demeanor\TestResult;

/**
 * An output writer interface that uses symfony's console component
 *
 * @since   0.1
 */
class ConsoleOutputWriter implements OutputWriter
{
    private $consoleOutput;

    public function __construct(OutputInterface $out)
    {
        $this->consoleOutput = $out;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($message, $verbosity=self::VERBOSITY_NORMAL)
    {
        if (!$this->canWrite($verbosity)) {
            return;
        }

        $this->consoleOutput->writeln($message);
    }

    /**
     * {@inheritdoc}
     */
    public function writeResult(TestCase $testcase, TestResult $result)
    {
        $this->writeln(sprintf(
            '%s: %s',
            $testcase->getName(),
            $this->getResultStatus($result)
        ), self::VERBOSITY_QUIET);

        foreach ($result->getMessages() as $messageType => $messages) {
            $verbosity = $this->getVerbosityForMessageType($messageType, $result);
            foreach ($messages as $msg) {
                $this->writeln("  {$msg}", $verbosity);
            }
        }
    }

    private function getResultStatus(TestResult $result)
    {
        $tag = 'info';
        $status = 'Passed';
        if ($result->errored()) {
            $tag = 'error';
            $status = 'Error';
        } elseif ($result->skipped()) {
            $tag = 'comment';
            $status = 'Skipped';
        } elseif ($result->failed()) {
            $tag = 'error';
            $status = 'Failed';
        }

        return sprintf('<%1$s>%2$s</%1$s>', $tag, $status);
    }

    private function canWrite($verbosity)
    {
        return $verbosity <= $this->consoleOutput->getVerbosity();
    }

    private function getVerbosityForMessageType($messageType, TestResult $result)
    {
        // if we didn't get a successful result, we want to print everything
        if (!$result->successful()) {
            return self::VERBOSITY_NORMAL;
        }

        switch (strtolower($messageType)) {
            case 'log':
                return self::VERBOSITY_VERBOSE;
                break;
            case 'skip':
            case 'error':
            case 'fail':
            default:
                return self::VERBOSITY_NORMAL;
                break;
        }
    }
}
