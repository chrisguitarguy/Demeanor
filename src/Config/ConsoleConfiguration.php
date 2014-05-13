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

namespace Demeanor\Config;

use Symfony\Component\Console\Input\InputInterface;
use Demeanor\Exception\ConfigurationException;

/**
 * A configuration object that's aware of the Symfony InputInterface and serves
 * as a decorator for a file based configureation.
 *
 * @since   0.1
 */
class ConsoleConfiguration implements Configuration
{
    private $consoleInput;
    private $wrappedConfig;

    public function __construct(Configuration $config, InputInterface $in)
    {
        $this->wrappedConfig = $config;
        $this->consoleInput = $in;
    }

    /**
     * {@inheritdoc}
     */
    public function setFile($filename)
    {
        $this->wrappedConfig->setFile($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        if ($confFile = $this->consoleInput->getOption('config')) {
            $this->setFile($confFile);
        }

        $this->wrappedConfig->initialize();

        if ($suiteName = $this->consoleInput->getOption('testsuite')) {
            $suites = $this->wrappedConfig->getTestSuites();
            if (!isset($suites[$suiteName])) {
                throw new ConfigurationException(sprintf('Test suite "%s" does not exist', $suiteName));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTestSuites()
    {
        $suites = $this->wrappedConfig->getTestSuites();
        if ($suiteName = $this->consoleInput->getOption('testsuite')) {
            return [
                $suiteName => $suites[$suiteName]
            ];
        }

        return $suites;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventSubscribers()
    {
        return $this->wrappedConfig->getEventSubscribers();
    }
}
