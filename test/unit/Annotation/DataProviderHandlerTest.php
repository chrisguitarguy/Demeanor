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

namespace Demeanor\Annotation;

function data_provider_function()
{
    return [
        'one'   => ['two'],
    ];
}

class DataProviderHandlerTest extends CallbackTestCase
{
    use \Counterpart\Assert;

    private $handler;

    public function __construct()
    {
        $this->handler = new DataProviderHandler();
    }

    public function testMethodInPositionalsCallsMethodAndAttachesProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider(['providerMethod'], []);

        $this->handler->onSetup($provider, $tc);
    }

    public function testMethodInNamedCallsMethodAndAttachesProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider([], ['method' => 'providerMethod']);

        $this->handler->onSetup($provider, $tc);
    }

    public function testFunctionInPositionalCallsFunctionAndAttachesProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider([__NAMESPACE__.'\\data_provider_function'], []);

        $this->handler->onSetup($provider, $tc);
    }

    public function testFunctionInNamedCallsFunctionAndAttachesProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider([], ['function' => __NAMESPACE__.'\\data_provider_function']);

        $this->handler->onSetup($provider, $tc);
    }

    public function testValidProviderInPositionsIsUsedAsDataProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider([['one' => ['two']]], []);

        $this->handler->onSetup($provider, $tc);
    }

    public function testValidProviderInNamedIsUsedAsDataProvider()
    {
        $tc = $this->testcase();
        $this->withProvider($tc);
        $provider = new DataProvider([], ['data' => ['one' => ['two']]]);

        $this->handler->onSetup($provider, $tc);
    }

    public function testNoDataProviderFoundDoesNothing()
    {
        $tc = $this->testcase();
        $tc->shouldReceive('withProvider')
            ->never();
        $provider = new DataProvider([], []);

        $this->handler->onSetup($provider, $tc);
    }

    public static function providerMethod()
    {
        return [
            'one'   => ['two'],
        ];
    }

    private function withProvider($tc)
    {
        $tc->shouldReceive('withProvider')
            ->atLeast(1);
    }
}
