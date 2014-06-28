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

class DefaultCleanerTest
{
    private $cleaner;
    private $validConfig = [
        'testsuites'    => [
            'aSuite'    => [],
        ],
    ];

    public function __construct()
    {
        $this->cleaner = new DefaultCleaner();
    }

    public function testEmptyTestSuitesThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->cleaner->cleanConfig([]);
    }

    public function testNonAssociativeArrayForTestSuitesThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->cleaner->cleanConfig([
            'testsuites'    => 'not an array',
        ]);
    }

    public function testSuiteWithNonAssociativeArrayThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->cleaner->cleanConfig([
            'testsuites'    => [
                'one'   => ['not', 'a', 'associative', 'array'],
            ]
        ]);
    }

    public function testSuiteWithNonStringTypeThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->cleaner->cleanConfig([
            'testsuites'    => [
                'one'   => [
                    'type'  => ['not', 'a', 'string'],
                ],
            ],
        ]);
    }

    public function testSuiteWithNonArrayExcludeThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->cleaner->cleanConfig([
            'testsuites'    => [
                'one'   => [
                    'exclude'   => 'not an associative array',
                ],
            ],
        ]);
    }

    public function testCleanConfigConvertsTestSuitesIntoSomethingUsable()
    {
        $keys = ['directories', 'glob', 'files'];

        $cleaned = $this->cleaner->cleanConfig([
            'testsuites'    => [
                'one'   => [],
            ],
        ]);

        $suites = $cleaned['testsuites'];
        Assert::assertArrayHasKey('type', $suites['one'], 'suite type should be passed through');
        Assert::assertArrayHasKey('exclude', $suites['one'], 'suite should have a default exclude');
        Assert::assertArrayHasKey('bootstrap', $suites['one'], 'suite should have a default bootstrap');
        foreach ($keys as $kn) {
            Assert::assertArrayHasKey($kn, $suites['one']);
            Assert::assertArrayHasKey($kn, $suites['one']['exclude']);
        }
    }

    public function testBadSuiteNameInDefaultSuitesThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['default-suites'] = 'does not exist';
        $this->cleaner->cleanConfig($this->validConfig);
    }

    public function testNonStringSubscriberThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['subscribers'] = [123];
        $this->cleaner->cleanConfig($this->validConfig);
    }

    public function testNonExistentClassAsSubscriberThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['subscribers'] = ['ThisClassDoesNotExistAtAll'];
        $this->cleaner->cleanConfig($this->validConfig);
    }

    public function testBadSubscriberClassThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['subscribers'] = __CLASS__;
        $this->cleaner->cleanConfig($this->validConfig);
    }

    public static function validSubscribers()
    {
        return [
            'as_string'     => 'Demeanor\\Subscriber\\MockerySubscriber',
            'as_object'     => new \Demeanor\Subscriber\MockerySubscriber(),
        ];
    }

    /**
     * @Provider("validSubscribers")
     */
    public function testValidSubscribersAreAllowedThrough(TestContext $ctx, $sub)
    {
        $this->validConfig['subscribers'] = $sub;

        $cleaned = $this->cleaner->cleanConfig($this->validConfig);

        Assert::assertArrayHasKey('subscribers', $cleaned);
        Assert::assertCount(1, $cleaned['subscribers']);
        Assert::assertInstanceOf('Demeanor\\Event\\Subscriber', $cleaned['subscribers'][0]);
    }

    public function testNonArrayCoverageThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['coverage'] = 'not an array';

        $this->cleaner->cleanConfig($this->validConfig);
    }

    public function testNonArrayCoverageReportsThrowsException(TestContext $ctx)
    {
        $this->willThrow($ctx);
        $this->validConfig['coverage'] = [
            'reports'   => ['not', 'an', 'associative', 'array'],
        ];

        $this->cleaner->cleanConfig($this->validConfig);
    }

    private function willThrow(TestContext $ctx)
    {
        $ctx->expectException('Demeanor\\Exception\\ConfigurationException');
    }
}
