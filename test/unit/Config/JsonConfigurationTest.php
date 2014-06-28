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
use Demeanor\TestContext;

class JsonConfigurationTest
{
    public function testInitializeThrowsWhenANonExistentConfigurationFileIsSet(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration();
        $config->setFile(__DIR__ . '/does/not/exist.json');

        $config->initialize();
    }

    public function testInitializeThrowsWhenAConfigurationFileCannotBeFound(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/does/not/exist.json']);
        $config->initialize();
    }

    public function testInitializeThrowsWhenAConfigurationFileContainsInvalidJson(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/invalid_config.json']);
        $config->initialize();
    }

    public function testSuiteCanRunReturnsTrueWhenNoDefaultSuitesAreSet(TestContext $ctx)
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);

        Assert::assertTrue($config->suiteCanRun('aSuite'));
    }

    public function testSuiteCanRunReturnsTrueWhenSuiteIsInDefaultSuites()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validdefault_config.json']);

        Assert::assertTrue($config->suiteCanRun('a_suite'));
        Assert::assertFalse($config->suiteCanRun('not_runnable'));
    }

    public function testConfigWithoutSubscribersReturnsEmptyArrayFromGetEventSubscribers()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);

        $subs = $config->getEventSubscribers();

        Assert::assertType('array', $subs);
        Assert::assertEmpty($subs);
    }

    public function testGetEventSubscribersReturnInstanceOfSubscriberWhenGivenValidConfig()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validsubscriber_config.json']);

        $subs = $config->getEventSubscribers();

        Assert::assertType('array', $subs);
        foreach ($subs as $sub) {
            Assert::assertInstanceOf('Demeanor\\Event\\Subscriber', $sub);
        }
    }

    public function testGetFiltersReturnsAFilterImplementation()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);

        Assert::assertInstanceOf('Demeanor\\Filter\\Filter', $config->getFilters());
    }

    public function testCoverageEnabledReturnsFalseWithEmptyReports()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);

        Assert::assertFalse($config->coverageEnabled());
    }

    public function testCoverageEnabledReturnsTrueWithNonEmptyReportArray()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validcoverage_config.json']);

        Assert::assertTrue($config->coverageEnabled());
    }

    public function testCoverageFinderReturnsInstanceOfFinder()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validcoverage_config.json']);

        Assert::assertInstanceOf('Demeanor\\Finder\\Finder', $config->coverageFinder());
    }

    public function testCoverageReportsReturnArrayOfReports()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validcoverage_config.json']);

        Assert::assertType('array', $config->coverageReports());
    }

    private function expect(TestContext $ctx)
    {
        $ctx->expectException('Demeanor\\Exception\\ConfigurationException');
    }
}
