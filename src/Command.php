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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Demeanor\Config\JsonConfiguration;
use Demeanor\Config\ConsoleConfiguration;
use Demeanor\Output\ConsoleOutputWriter;

class Command extends BaseCommand
{
    const NAME = 'demeanor:run';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::NAME);
        $this->setDescription('Run all or some of the test suites');
        $this->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'The path to a valid configuration file'
        );
        $this->addOption(
            'testsuite',
            's',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'The test suite to run'
        );
        $this->addOption(
            'all',
            'a',
            InputOption::VALUE_NONE,
            'Run all the test suites'
        );
        $this->addOption(
            'filter-name',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Filter test cases by name'
        );
        $this->addOption(
            'no-coverage',
            null,
            InputOption::VALUE_NONE,
            'Turn off all coverage reports'
        );
        $this->addOption(
            'coverage-html',
            null,
            InputOption::VALUE_REQUIRED,
            'Generate an HTML coverage report in the provided directory'
        );
        $this->addOption(
            'coverage-text',
            null,
            InputOption::VALUE_REQUIRED,
            'Generate a text based coverage report to the provide filename'
        );
        $this->addOption(
            'coverage-diff',
            null,
            InputOption::VALUE_REQUIRED,
            'Generate a diff-file coverage report to the provided directory'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $writer = new ConsoleOutputWriter($out);
        $demeanor = new Demeanor($writer, new ConsoleConfiguration(new JsonConfiguration(), $in));
        return $demeanor->run();
    }
}
