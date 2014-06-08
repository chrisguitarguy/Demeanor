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
use Demeanor\Event\Subscriber;

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

    public function testInitializeThrowsWhenConfigurationDoesNotDefineAnyTestSuites(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/nosuites_config.json']);
        $config->initialize();
    }

    public function testInitializeThrowsWhenTestSuiteConfigurationIsNotAnAssociativeArray(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/badsuites_config.json']);
        $config->initialize();
    }

    public function testInitializeThrowsWhenInvidualTestSuiteConfigIsNotAssociativeArray(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/badsuite_config.json']);
        $config->initialize();
    }

    public function testInitializeThrowsWhenExcludeIsNotAnAssociativeArray(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__.'/../Fixtures/badexclude_config.json']);
        $config->initialize();
    }

    public function testConfigurationNormalizesTestSuiteConfig()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);
        $config->initialize();
        $suites = $config->getTestSuites();
        $suite = array_pop($suites);

        Assert::assertType('array', $suite);
        Assert::assertType('array', $suite['bootstrap']);
        Assert::assertType('array', $suite['exclude']);
        foreach (['files', 'glob', 'directories'] as $kn) {
            Assert::assertType('array', $suite[$kn]);
            Assert::assertType('array', $suite['exclude'][$kn]);
        }
    }

    public function testSuiteCanRunReturnsTrueWhenNoDefaultSuitesAreSet(TestContext $ctx)
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);
        $config->initialize();

        Assert::assertTrue($config->suiteCanRun('aSuite'));
    }

    public function testInitializeThrowsWhenDefaultSuitesIncludesNonExistentTestSuite(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/baddefault_config.json']);
        $config->initialize();
    }

    public function testSuiteCanRunReturnsTrueWhenSuiteIsInDefaultSuites()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validdefault_config.json']);
        $config->initialize();

        Assert::assertTrue($config->suiteCanRun('a_suite'));
        Assert::assertFalse($config->suiteCanRun('not_runnable'));
    }

    public function testConfigWithoutSubscribersReturnsEmptyArrayFromGetEventSubscribers()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);
        $config->initialize();

        $subs = $config->getEventSubscribers();

        Assert::assertType('array', $subs);
        Assert::assertEmpty($subs);
    }

    public function testConfigWithNonStringSubscriberThrowsException(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/badsubscriber_config.json']);
        $config->initialize();
    }

    public function testSubscriberThatDoesNotExistThrowsException(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/nonexistsubscriber_config.json']);
        $config->initialize();
    }

    public function testSubscriberClassNameThatDoesNotImplementSubscriberThrows(TestContext $ctx)
    {
        $this->expect($ctx);
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/badimplementssubscriber_config.json']);
        $config->initialize();
    }

    public function testGetEventSubscribersReturnInstanceOfSubscriberWhenGivenValidConfig()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/validsubscriber_config.json']);

        $config->initialize();
        $subs = $config->getEventSubscribers();

        Assert::assertType('array', $subs);
        foreach ($subs as $sub) {
            Assert::assertInstanceOf('Demeanor\\Event\\Subscriber', $sub);
        }
    }

    public function testGetFiltersReturnsAFilterImplementation()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);
        $config->initialize();

        Assert::assertInstanceOf('Demeanor\\Filter\\Filter', $config->getFilters());
    }

    private function expect(TestContext $ctx)
    {
        $ctx->expectException('Demeanor\\Exception\\ConfigurationException');
    }
}
