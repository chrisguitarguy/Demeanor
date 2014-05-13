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

    public function testConfigurationNormalizesTestSuiteConfig()
    {
        $config = new JsonConfiguration([__DIR__ . '/../Fixtures/valid_config.json']);
        $config->initialize();
        $suites = $config->getTestSuites();
        $suite = array_pop($suites);

        Assert::assertType('array', $suite);
        foreach (['bootstrap', 'files', 'glob', 'directories'] as $kn) {
            Assert::assertType('array', $suite[$kn]);
        }
    }

    private function expect(TestContext $ctx)
    {
        $ctx->expectException('Demeanor\\Exception\\ConfigurationException');
    }
}
