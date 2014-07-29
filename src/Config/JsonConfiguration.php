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

use Demeanor\Filter\ConsensusChainFilter;
use Demeanor\Finder\FinderBuilder;
use Demeanor\Finder\ExcludingFinder;
use Demeanor\Exception\ConfigurationException;

/**
 * Load configuration from a JSON file.
 *
 * @since   0.1
 */
class JsonConfiguration implements Configuration
{
    private $search;
    private $cleaner;
    private $configFile = null;
    private $initialized = false;
    private $config = array();

    /**
     * Constructor. Optionally set the configuration files names for which the
     * `initialize` method will search.
     *
     * @since   0.1
     * @param   array $search
     * @return  void
     */
    public function __construct(array $search=null, Cleaner $cleaner=null)
    {
        $this->search = $search ?: [
            'demeanor.json',
            'demanor.dist.json'
        ];
        $this->cleaner = $cleaner ?: new DefaultCleaner();
    }

    /**
     * {@inheritdoc}
     */
    public function setFile($filename)
    {
        $this->configFile = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $config = $this->loadConfigFile();
        $this->config = $this->cleaner->cleanConfig($config);
        $this->initialized = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestSuites()
    {
        $this->initialize();
        return $this->config['testsuites'];
    }

    /**
     * {@inheritdoc}
     */
    public function suiteCanRun($suiteName)
    {
        $this->initialize();

        if (empty($this->config['default-suites'])) {
            return true;
        }

        return in_array($suiteName, $this->config['default-suites']);
    }

    /**
     * {@inheritdoc}
     */
    public function getEventSubscribers()
    {
        $this->initialize();
        return $this->config['subscribers'];
    }

    /**
     * {@inheritdoc}
     * There is no filtering done by default in JsonConfiguration, so return
     * an empty chain.
     */
    public function getFilters()
    {
        $this->initialize();
        return new ConsensusChainFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function coverageEnabled()
    {
        $this->initialize();
        $reports = $this->coverageReports();
        return !empty($reports);
    }

    /**
     * {@inheritdoc}
     */
    public function coverageFinder()
    {
        $this->initialize();

        $whitelist = FinderBuilder::create()
            ->withDirectories($this->config['coverage']['directories'], '.php')
            ->withFiles($this->config['coverage']['files'])
            ->withGlobs($this->config['coverage']['glob'])
            ->build();

        $blacklist = FinderBuilder::create()
            ->withDirectories($this->config['coverage']['exclude']['directories'], '.php')
            ->withFiles($this->config['coverage']['exclude']['files'])
            ->withGlobs($this->config['coverage']['exclude']['glob'])
            ->build();

        return new ExcludingFinder($whitelist, $blacklist);
    }

    /**
     * {@inheritdoc}
     */
    public function coverageReports()
    {
        $this->initialize();

        return $this->config['coverage']['reports'];
    }

    private function loadConfigFile()
    {
        $fn = $this->locateConfigFile();
        if (!$fn) {
            throw new ConfigurationException(sprintf(
                'Could not locate configuration file, searched for %s',
                implode(' or ', $this->search)
            ));
        }
        if (!file_exists($fn)) {
            throw new ConfigurationException(sprintf('Configuration file %s does not exist', $fn));
        }

        $config = json_decode(file_get_contents($fn), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigurationException(sprintf('Error parsing JSON in %s', $fn));
        }

        return $config;
    }

    private function locateConfigFile()
    {
        if (null !== $this->configFile) {
            return $this->configFile;
        }

        foreach ($this->search as $file) {
            if (file_exists($file)) {
                $this->configFile = $file;
                break;
            }
        }

        return $this->configFile;
    }
}
