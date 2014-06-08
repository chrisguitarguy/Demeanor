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

use Counterpart\Assert;
use Symfony\Component\Console\Input\ArrayInput;
use Demeanor\Filter\Filter;
use Demeanor\Filter\NameFilter;

class ConsoleConfigurationTest
{
    const DEFAULT_SUITE = 'one';

    private $consoleInput;
    private $wrappedConfig;
    private $consoleConfig;

    public function __construct()
    {
        $cmd = new \Demeanor\Command();
        $this->consoleInput = new ArrayInput([], $cmd->getDefinition());
        $this->wrappedConfig = \Mockery::mock('Demeanor\\Config\\Configuration');
        $this->consoleConfig = new ConsoleConfiguration($this->wrappedConfig, $this->consoleInput);
    }

    public function testSetFileProxiesToWrappedConfiguration()
    {
        $this->willSetConfig();
        $this->consoleConfig->setFile('afile.json');
    }

    public function testConfigFileOptionSetsFilesOnInitialize()
    {
        $this->willSetConfig();
        $this->willInitialize();
        $this->consoleHasOption('config', 'a_file.json');

        $this->consoleConfig->initialize();
    }

    /**
     * @Expect("Demeanor\Exception\ConfigurationException")
     */
    public function testCombiningTestSuiteAndAllOptionThrowsException()
    {
        $this->willInitialize();
        $this->consoleHasOption('all', true);
        $this->consoleHasOption('testsuite', 'a_suite');

        $this->consoleConfig->initialize();
    }

    /**
     * @Expect("Demeanor\Exception\ConfigurationException")
     */
    public function testInvalidTestSuiteInCommandLineThrowsException()
    {
        $this->willInitialize();
        $this->hasTestSuites(['one' => ['type' => 'unit']]);
        $this->consoleHasOption('testsuite', ['two']);

        $this->consoleConfig->initialize();
    }

    public function testGetTestSuitesProxiesToWrappedConnection()
    {
        $this->hasTestSuites();
        Assert::assertArrayHasKey(self::DEFAULT_SUITE, $this->consoleConfig->getTestSuites());
    }

    public function testSuiteCanRunAlwaysReturnsTrueWithAllOption()
    {
        $this->consoleHasOption('all', true);
        Assert::assertTrue($this->consoleConfig->suiteCanRun('a_suite'));
    }

    public function testSuiteCanRunOnlyAllowsSuitesInCliOptions()
    {
        $this->consoleHasOption('testsuite', ['one']);
        Assert::assertTrue($this->consoleConfig->suiteCanRun('one'));
        Assert::assertFalse($this->consoleConfig->suiteCanRun('two'));
    }

    public function testSuiteCanRunWithoutCliOptionsProxiesToWrappedConfig()
    {
        $suite = 'a_suite';
        $this->wrappedConfig->shouldReceive('suiteCanRun')
            ->once()
            ->with($suite)
            ->andReturn(true);

        Assert::assertTrue($this->consoleConfig->suiteCanRun($suite));
    }

    public function testGetEventSubscribersProxiesToWrappedConfig()
    {
        $this->wrappedConfig->shouldReceive('getEventSubscribers')
            ->once()
            ->andReturn([]);

        Assert::assertEquals([], $this->consoleConfig->getEventSubscribers());
    }

    public function testGetFiltersWithWrappedConfigReturningNonChainConvertsToChainFilter()
    {
        $filter = new NameFilter('a_name');
        $this->hasFilters($filter);

        Assert::assertInstanceOf('Demeanor\\Filter\\ChainFilter', $this->consoleConfig->getFilters());
    }

    public function testFilterNameOptionCausesFiltersToBeAddedToTheChain()
    {
        $filter = \Mockery::mock('Demeanor\\Filter\\ChainFilter');
        $filter->shouldReceive('addFilter')
            ->with(\Mockery::type('Demeanor\\Filter\\NameFilter'))
            ->once();
        $this->hasFilters($filter);
        $this->consoleHasOption('filter-name', ['one name']);

        Assert::assertIdentical($filter, $this->consoleConfig->getFilters());
    }

    private function consoleHasOption($name, $value=null)
    {
        $this->consoleInput->setOption($name, $value);
    }

    private function willSetConfig()
    {
        $this->wrappedConfig->shouldReceive('setFile')
            ->atLeast(1);
    }

    private function willInitialize()
    {
        $this->wrappedConfig->shouldReceive('initialize')
            ->atLeast(1);
    }

    private function hasTestSuites(array $suites=null)
    {
        $suites = $suites ?: [
            self::DEFAULT_SUITE     => ['type' => 'unit'],
        ];

        $this->wrappedConfig->shouldReceive('getTestSuites')
            ->atLeast(1)
            ->andReturn($suites);
    }

    private function hasFilters(Filter $filter)
    {
        $this->wrappedConfig->shouldReceive('getFilters')
            ->atLeast(1)
            ->andReturn($filter);
    }
}
