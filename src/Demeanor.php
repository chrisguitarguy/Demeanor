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

use Demeanor\Exception\ConfigurationException;

/**
 * The main application class.
 *
 * @since   0.1
 */
final class Demeanor
{
    const VERSION   = '0.1';
    const NAME      = 'Demeanor';

    private $outputWriter;

    public function __construct(OutputWriter $writer)
    {
        $this->outputWriter = $writer;
    }

    public function run()
    {
        try {
            $testsuites = $this->loadTestSuites();
        } catch (\Exception $e) {
            $this->outputWriter->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        $hasErrors = false;
        foreach ($testsuites as $testsuite) {
            try {
                $hasErrors = $this->runTestSuite($testsuite);
            } catch (\Exception $e) {
                // TODO
            }
        }

        return $hasErrors ? 1 : 0;
    }

    private function loadTestSuites()
    {
        $config = $this->loadConfiguration();
        if (empty($config['testsuites'])) {
            throw new ConfigurationException('No Test suites defined in configuration');
        }

        if (!is_array($config['testsuites'])) {
            throw new ConfigurationException('`testsuites` configuration much be an object');
        }

        $factory = new TestSuiteFactory();
        $suites = array();
        foreach ($config['testsuites'] as $name => $suiteConfig) {
            $suites[] = $factory->create($name, $suiteConfig);
        }

        return $suites;
    }

    private function loadConfiguration()
    {
        $files = ['demeanor.json', 'demeanor.dist.json'];
        $configFile = null;
        foreach ($files as $file) {
            if (file_exists($file)) {
                $configFile = $file;
                break;
            }
        }

        if (!$configFile) {
            throw new ConfigurationException('No '.implode(' or ', $files). ' configuration file found');
        }

        $json = file_get_contents($configFile);
        $config = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // XXX figure how to get the json error here and give a better message
            throw new ConfigurationException('Could not load configuration json file');
        }

        return $config;
    }

    /**
     * Run a single test suite.
     *
     * @since   0.1
     * @param   TestSuite $suite
     * @return  boolean True if errors were encountered.
     */
    private function runTestSuite(TestSuite $suite)
    {
        $suite->bootstrap();
        $tests = $suite->load();

        $errors = false;
        foreach ($tests as $test) {
            $result = $test->run();
            if (!$result->successful() && !$result->skipped()) {
                $errors = true;
            }
            $this->outputWriter->writeResult($test, $result);
        }

        return $errors;
    }

    private function writeMessage(OutputInterface $out, array $messages)
    {
        foreach ($messages as $messageType => $typeMessages) {
            foreach ($typeMessages as $msg) {
                $out->writeln("  {$messageType} - {$msg}");
            }
        }
    }
}
