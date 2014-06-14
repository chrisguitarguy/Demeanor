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

use Demeanor\Event\Subscriber;
use Demeanor\Filter\ChainFilter;
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
    private $configFile = null;
    private $config = array();

    /**
     * Constructor. Optionally set the configuration files names for which the
     * `initialize` method will search.
     *
     * @since   0.1
     * @param   array $search
     * @return  void
     */
    public function __construct(array $search=null)
    {
        $this->search = $search ?: [
            'demeanor.json',
            'demanor.dist.json'
        ];
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
        $this->loadConfigFile();
        $this->validateTestSuites();
        $this->validateDefaultSuites();
        $this->validateEventSubscribers();
        $this->validateCoverage();
    }

    /**
     * {@inheritdoc}
     */
    public function getTestSuites()
    {
        return $this->config['testsuites'];
    }

    /**
     * {@inheritdoc}
     */
    public function suiteCanRun($suiteName)
    {
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
        return $this->config['subscribers'];
    }

    /**
     * {@inheritdoc}
     * There is no filtering done by default in JsonConfiguration, so return
     * an empty chain.
     */
    public function getFilters()
    {
        return new ChainFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function coverageEnabled()
    {
        return !empty($this->coverageReports());
    }

    /**
     * {@inheritdoc}
     */
    public function coverageFinder()
    {
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

        $this->config = json_decode(file_get_contents($fn), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigurationException(sprintf('Error parsing JSON in %s', $fn));
        }
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

    private function validateTestSuites()
    {
        if (empty($this->config['testsuites'])) {
            throw new ConfigurationException('No test suites defined');
        }
        if (!$this->isAssociativeArray($this->config['testsuites'])) {
            throw new ConfigurationException('`testsuites` configuration must be an object');
        }

        $testsuites = array();
        foreach ($this->config['testsuites'] as $name => $suiteConfig) {
            if (!$this->isAssociativeArray($suiteConfig)) {
                throw new ConfigurationException(sprintf(
                    "Test suite %s's configuration is not an object",
                    $name
                ));
            }

            $suiteConfig = $this->setSuiteDefaults($suiteConfig);
            if (!is_string($suiteConfig['type'])) {
                throw new ConfigurationException(sprintf(
                    'Test suite `type` argument is not a string in suite %s',
                    $name
                ));
            }

            if (!$this->isAssociativeArray($suiteConfig['exclude'])) {
                throw new ConfigurationException(sprintf(
                    "Test suite %s's `exclude` is not an object",
                    $name
                ));
            }

            $suiteConfig['bootstrap'] = $this->ensureArray($suiteConfig['bootstrap']);

            foreach (['directories', 'files', 'glob'] as $kn) {
                $suiteConfig[$kn] = $this->ensureArray($suiteConfig[$kn]);
                $suiteConfig['exclude'][$kn] = $this->ensureArray($suiteConfig['exclude'][$kn]);
            }

            $testsuites[$name] = $suiteConfig;
        }

        $this->config['testsuites'] = $testsuites;
    }

    private function validateDefaultSuites()
    {
        if (empty($this->config['default-suites'])) {
            $this->config['default-suites'] = null;
            return;
        }

        if (!is_array($this->config['default-suites'])) {
            $this->config['default-suites'] = [$this->config['default-suites']];
        }

        foreach ($this->config['default-suites'] as $sn) {
            if (!isset($this->config['testsuites'][$sn])) {
                throw new ConfigurationException(sprintf(
                    'Test suite "%s" in `default-suites` does not exist',
                    $sn
                ));
            }
        }
    }

    private function setSuiteDefaults(array $config)
    {
        return array_replace_recursive([
            'type'          => 'unit',
            'bootstrap'     => array(),
            'directories'   => array(),
            'files'         => array(),
            'glob'          => array(),
            'exclude'       => [
                'directories'   => array(),
                'files'         => array(),
                'glob'          => array(),
            ],
        ], $config);
    }

    private function validateEventSubscribers()
    {
        if (empty($this->config['subscribers'])) {
            $this->config['subscribers'] = array();
            return;
        }

        if (!is_array($this->config['subscribers'])) {
            $this->config['subscribers'] = [$this->config['subscribers']];
        }

        $subs = array();
        foreach ($this->config['subscribers'] as $cls) {
            $subs[] = $this->createSubscriber($cls);
        }

        $this->config['subscribers'] = $subs;
    }

    private function createSubscriber($cls)
    {
        if (!is_string($cls)) {
            throw new ConfigurationException('Subscriber class names must be strings');
        }

        if (!class_exists($cls)) {
            throw new ConfigurationException(sprintf(
                "Class %s cannot be added as a subscriber because it doesn't exist",
                $cls
            ));
        }

        $obj = new $cls();
        if (!$obj instanceof Subscriber) {
            throw new ConfigurationException(sprintf(
                "Class %s could not be added as a subscriber because it doesn't implement Demeanor\\Event\\Subscriber",
                $cls
            ));
        }

        return $obj;
    }

    private function validateCoverage()
    {
        if (!empty($this->config['coverage']) && !$this->isAssociativeArray($this->config['coverage'])) {
            throw new ConfigurationException('"coverage" configuration must be a JSON object');
        }

        $coverage = array_replace_recursive([
            'reports'       => array(),
            'directories'   => array(),
            'files'         => array(),
            'glob'          => array(),
            'exclude'       => [
                'directories'   => array(),
                'files'         => array(),
                'glob'          => array(),
            ],
        ], empty($this->config['coverage']) ? array() : $this->config['coverage']);

        if (!empty($coverage['reports']) && !$this->isAssociativeArray($coverage['reports'])) {
            throw new ConfigurationException('Coverage reports must be a JSON object');
        }

        foreach (['directories', 'files', 'glob'] as $kn) {
            $coverage[$kn] = $this->ensureArray($coverage[$kn]);
            $coverage['exclude'][$kn] = $this->ensureArray($coverage['exclude'][$kn]);
        }

        $this->config['coverage'] = $coverage;
    }

    private function isAssociativeArray($obj)
    {
        if (!is_array($obj)) {
            return false;
        }

        reset($obj);
        if (!is_string(key($obj))) {
            return false;
        }

        return true;
    }

    private function ensureArray($mixed)
    {
        if (!is_array($mixed)) {
            $mixed = [$mixed];
        }

        return $mixed;
    }
}
