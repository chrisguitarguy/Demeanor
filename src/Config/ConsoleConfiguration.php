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
use Demeanor\Filter\ChainFilter;
use Demeanor\Filter\ConsensusChainFilter;
use Demeanor\Filter\AffirmativeChainFilter;
use Demeanor\Filter\NameFilter;
use Demeanor\Filter\GroupFilter;
use Demeanor\Filter\FileFilter;
use Demeanor\Filter\DirectoryFilter;
use Demeanor\Filter\NegatingFilter;

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
    private $initialized = false;

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
        if ($this->initialized) {
            return;
        }

        if ($confFile = $this->consoleInput->getOption('config')) {
            $this->setFile($confFile);
        }

        $this->wrappedConfig->initialize();

        if ($suiteNames = $this->consoleInput->getOption('testsuite')) {
            if ($this->consoleInput->getOption('all')) {
                throw new ConfigurationException('The --all option cannot be combined with --testsuite');
            }

            $invalid = array_diff($suiteNames, array_keys($this->wrappedConfig->getTestSuites()));
            if ($invalid) {
                throw new ConfigurationException(sprintf(
                    'Invalid test suite name(s): %s',
                    implode(', ', $invalid)
                ));
            }
        }

        $this->initialized = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestSuites()
    {
        $this->initialize();
        return $this->wrappedConfig->getTestSuites();
    }

    /**
     * {@inheritdoc}
     */
    public function suiteCanRun($suiteName)
    {
        $this->initialize();

        if ($this->consoleInput->getOption('all')) {
            return true;
        }

        if ($sns = $this->consoleInput->getOption('testsuite')) {
            return in_array($suiteName, $sns, true);
        }

        return $this->wrappedConfig->suiteCanRun($suiteName);
    }

    /**
     * {@inheritdoc}
     */
    public function getEventSubscribers()
    {
        $this->initialize();

        return $this->wrappedConfig->getEventSubscribers();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $this->initialize();

        $chain = $this->wrappedConfig->getFilters();
        if (!$chain instanceof ChainFilter) {
            $chain = new ConsensusChainFilter([$chain]);
        }

        $nameFilters = $this->consoleInput->getOption('filter-name') ?: array();
        foreach ($nameFilters as $name) {
            $chain->addFilter(new NameFilter($name));
        }

        $includeGroups = $this->consoleInput->getOption('include-group') ?: array();
        foreach ($includeGroups as $group) {
            $chain->addFilter(new GroupFilter($group));
        }

        $excludeGroups = $this->consoleInput->getOption('exclude-group') ?: array();
        foreach ($excludeGroups as $group) {
            $chain->addFilter(new NegatingFilter(new GroupFilter($group)));
        }

        $paths = $this->consoleInput->getArgument('path');
        if ($paths) {
            $pathFilter = new AffirmativeChainFilter();
            foreach ($paths as $path) {
                $pathFilter->addFilter(is_dir($path) ? new DirectoryFilter($path) : new FileFilter($path));
            }
            $chain->addFilter($pathFilter);
        }

        return $chain;
    }

    /**
     * {@inheritdoc}
     */
    public function coverageEnabled()
    {
        $this->initialize();

        if ($this->consoleInput->getOption('no-coverage')) {
            return false;
        }

        $reports = $this->coverageReports();
        return !empty($reports);
    }

    /**
     * {@inheritdoc}
     */
    public function coverageFinder()
    {
        $this->initialize();
        return $this->wrappedConfig->coverageFinder();
    }

    /**
     * {@inheritdoc}
     */
    public function coverageReports()
    {
        $this->initialize();

        $reports = $this->wrappedConfig->coverageReports();
        if ($html = $this->consoleInput->getOption('coverage-html')) {
            $reports['html'] = $html;
        }

        if ($diff = $this->consoleInput->getOption('coverage-diff')) {
            $reports['diff'] = $diff;
        }

        if ($text = $this->consoleInput->getOption('coverage-text')) {
            $reports['text'] = $text;
        }

        return $reports;
    }
}
