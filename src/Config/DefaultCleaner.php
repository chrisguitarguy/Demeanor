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
use Demeanor\Exception\ConfigurationException;

/**
 * Default implementation of `Cleaner`.
 *
 * @since   0.3
 */
class DefaultCleaner implements Cleaner
{
    /**
     * {@inheritdoc}
     */
    public function cleanConfig(array $config)
    {
        if (empty($config['testsuites'])) {
            throw new ConfigurationException('No test suites defined');
        }
        $config['testsuites'] = $this->cleanTestSuites($config['testsuites']);

        if (empty($config['default-suites'])) {
            $config['default-suites'] = null;
        } else {
            $config['default-suites'] = $this->cleanDefaultSuites($config['default-suites'], $config['testsuites']);
        }

        if (empty($config['subscribers'])) {
            $config['subscribers'] = array();
        } else {
            $config['subscribers'] = $this->cleanSubscribers($config['subscribers']);
        }

        $config['coverage'] = $this->cleanCoverage(
            isset($config['coverage']) ? $config['coverage'] : array()
        );

        return $config;
    }

    private function cleanTestSuites($suites)
    {
        if (!$this->isAssociativeArray($suites)) {
            throw new ConfigurationException('`testsuites` configuration must be an associative array');
        }

        $testsuites = array();
        foreach ($suites as $name => $suiteConfig) {
            if (!$this->isAssociativeArray($suiteConfig)) {
                throw new ConfigurationException(sprintf(
                    "Test suite %s's configuration is not an associative array",
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
                    "Test suite %s's `exclude` is not an associative array",
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

        return $testsuites;
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

    private function cleanDefaultSuites($defaults, array $testsuites)
    {
        $defaults = $this->ensureArray($defaults);

        foreach ($defaults as $sn) {
            if (!isset($testsuites[$sn])) {
                throw new ConfigurationException(sprintf(
                    'Test suite "%s" in `default-suites` does not exist',
                    $sn
                ));
            }
        }

        return $defaults;
    }

    private function cleanSubscribers($subs)
    {
        $subs = $this->ensureArray($subs);

        $cleaned = array();
        foreach ($subs as $sub) {
            if (!is_object($sub)) {
                $sub = $this->createSubscriber($sub);
            }

            if (!$sub instanceof Subscriber) {
                throw new ConfigurationException(sprintf(
                    "Class %s could not be added as a subscriber because it doesn't implement Demeanor\\Event\\Subscriber",
                    get_class($sub)
                ));
            }

            $cleaned[] = $sub;
        }

        return $cleaned;
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

        return new $cls();
    }

    private function cleanCoverage($coverage)
    {
        if (!$this->isAssociativeArray($coverage)) {
            throw new ConfigurationException('`coverage` configuration must be an associative array');
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
        ], $coverage);

        if (!$this->isAssociativeArray($coverage['reports'])) {
            throw new ConfigurationException('Coverage reports must be an associative array');
        }

        foreach (['directories', 'files', 'glob'] as $kn) {
            $coverage[$kn] = $this->ensureArray($coverage[$kn]);
            $coverage['exclude'][$kn] = $this->ensureArray($coverage['exclude'][$kn]);
        }

        return $coverage;
    }

    private function isAssociativeArray($obj)
    {
        if (!is_array($obj)) {
            return false;
        }

        reset($obj);
        if ($obj && !is_string(key($obj))) {
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
