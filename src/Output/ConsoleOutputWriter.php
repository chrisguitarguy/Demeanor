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
    public function writeln($message)
    {
        if (!$this->canWrite(OutputInterface::VERBOSITY_NORMAL)) {
            return;
        }

        $this->consoleOutput->writeln($message);
    }

    /**
     * {@inheritdoc}
     */
    public function writeResult(TestCase $testcase, TestResult $result)
    {
        switch ($this->getVerbosity()) {
            case OutputInterface::VERBOSITY_QUIET:
            case OutputInterface::VERBOSITY_NORMAL:
                $this->writeQuietResult($result);
                break;
            default:
                $this->writeNoisyResult($testcase, $result);
                break;
        }
    }

    private function writeQuietResult(TestResult $result)
    {
        $this->consoleOutput->write($this->getResultStatus($result, true));
    }

    private function writeNoisyResult(TestCase $testcase, TestResult $result)
    {
        $this->writeln(sprintf(
            '%s: %s',
            $testcase->getName(),
            $this->getResultStatus($result)
        ));

        if ($this->isVerbosity(OutputInterface::VERBOSITY_DEBUG)) {
            $this->consoleOutput->writeln(sprintf('Location: %s:%d', $testcase->filename(), $testcase->lineno()));
        }

        foreach ($result->getMessages() as $messageType => $messages) {
            $verbosity = $this->getVerbosityForMessageType($messageType, $result);
            if (!$this->canWrite($verbosity)) {
                continue;
            }

            foreach ($messages as $msg) {
                $this->consoleOutput->writeln($msg);
            }
        }

        $this->writeln('');
    }

    private function getResultStatus(TestResult $result, $short=false)
    {
        $tag = $short ? false : 'info';
        $status = $short ? '.' : 'Passed';
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

        if ($tag) {
            return sprintf('<%1$s>%2$s</%1$s>', $tag, $short ? $status[0] : $status);
        } else {
            return $short ? $status[0] : $status;
        }
    }

    private function canWrite($verbosity)
    {
        return $verbosity <= $this->getVerbosity();
    }

    private function isVerbosity($verbosity)
    {
        return $verbosity === $this->getVerbosity();
    }

    private function getVerbosity()
    {
        return $this->consoleOutput->getVerbosity();
    }

    private function getVerbosityForMessageType($messageType, TestResult $result)
    {
        // if we didn't get a successful result, we want to print everything
        if (!$result->successful()) {
            return OutputInterface::VERBOSITY_NORMAL;
        }

        switch (strtolower($messageType)) {
            case 'log':
                return OutputInterface::VERBOSITY_VERY_VERBOSE;
                break;
            case 'skip':
            case 'error':
            case 'fail':
            default:
                return OutputInterface::VERBOSITY_VERBOSE;
                break;
        }
    }
}
