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

namespace Demeanor\Phpt;

use Counterpart\Assert;
use Demeanor\TestContext;

class ParserTest
{
    private $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @Expect(exception="Demeanor\Exception\InvalidArgumentException")
     */
    public function testParseThrowsWhenGivenANonArrayOrTraversable()
    {
        $this->parser->parse('not an array or traversable');
    }

    /**
     * @Expect(exception="Demeanor\Exception\UnexpectedValueException")
     */
    public function testParseThrowsWhenTheFileDoesNotStartWithASection()
    {
        $this->parser->parse([
            'a line here',
            '--SECTION--',
            'part of SECTION',
        ]);
    }

    /**
     * @Expect(exception="Demeanor\Exception\UnexpectedValueException")
     */
    public function testParseThrowsWhenTheSameSectionIsEncounteredTwice()
    {
        $this->parser->parse([
            '--NAME--',
            'in a section',
            '--NAME--',
            'in the same section again',
        ]);
    }

    /**
     * @Provider(method="fileProvider")
     */
    public function testParseReturnsExpectedArrayWhenAFileIsParsedSuccessfully(TestContext $ctx, $file)
    {
        $sections = $this->parser->parse($file);

        Assert::assertArrayHasKey('TEST', $sections);
        Assert::assertArrayHasKey('FILE', $sections);
        Assert::assertStringContains('This is the test', $sections['TEST']);
        Assert::assertStringContains('is the file', $sections['FILE']);
    }

    public static function fileProvider()
    {
        $fn = __DIR__ . '/../Fixtures/sample.phpt';
        return [
            'array'         => [file($fn)], // has to wrapped in an array for the data provider stuff.
            'Traversable'   => new \SplFileObject($fn),
        ];
    }

    public function testParseTurnsEnvSectionIntoAssociativeArray()
    {
        $sections = $this->parser->parse(new \SplFileObject(__DIR__.'/../Fixtures/env_example.phpt'));

        Assert::assertArrayHasKey('ENV', $sections);
        Assert::assertType('array', $sections['ENV']);
        Assert::assertCount(2, $sections['ENV']);
    }
}
