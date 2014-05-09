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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Demeanor\Exception\ConfigurationException;

/**
 * The main application class.
 *
 * @since   0.1
 */
final class Demeanor extends Command
{
    const VERSION   = '0.1';
    const NAME      = 'demeanor';

    /**
     * The main application entry point.
     * {@inheritdoc}
     */
    protected function execute(InputInterface $in, OutputInterface $out)
    {
        try {
            $testsuites = $this->loadTestSuites();
        } catch (\Exception $e) {
            $out->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        foreach ($testsuites as $testsuite) {
            try {
                $this->runTestSuite($testsuite, $out);
            } catch (\Exception $e) {

            }
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::NAME);
        $this->setDescription('Run all or a single test suite');
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

    private function runTestSuite(TestSuite $suite, OutputInterface $out)
    {
        $suite->bootstrap();
        $tests = $suite->load();

        foreach ($tests as $test) {
            $result = $test->run();
            $out->writeln($test->getName() . ': ');
        }
    }
}
