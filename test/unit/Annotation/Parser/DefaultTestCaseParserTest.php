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

namespace Demeanor\Annotation\Parser;

use Chrisguitarguy\Annotation\Parser as AnnotParser;
use Demeanor\TestCaseStub;

/**
 * This is much closer to an integration test than a unit test.
 */
class DefaultTestCaseParserTest
{
    use \Counterpart\Assert;

    private $reader, $parser;

    public function __construct()
    {
        $this->reader = \Mockery::mock('Demeanor\\Annotation\\Reader\\DocblockReader');
        $this->parser = new DefaultTestCaseParser(new AnnotParser(), $this->reader);
    }

    public function testNoDocblocksReturnsNoAnnotationObjects()
    {
        $this->readerReturnsDocblocks([]);
        $annotations = $this->parser->annotationsFor($this->testcase());
        $this->assertType('array', $annotations);
        $this->assertEmpty($annotations);
    }

    public function testSyntaxErrorInDocblocksSkipsDocblock()
    {
        $this->readerReturnsDocblock('/** @Before("this is broken" */');
        $annotations = $this->parser->annotationsFor($this->testcase());
        $this->assertType('array', $annotations);
        $this->assertEmpty($annotations);
    }

    public function testUnregisteredAnnotationIsNotAddedToReturnedAnnotations()
    {
        $this->readerReturnsDocblock('/** @ThisDoesNotExist("here") */');
        $annotations = $this->parser->annotationsFor($this->testcase());
        $this->assertType('array', $annotations);
        $this->assertEmpty($annotations);
    }

    public function testValidAnnotationInDocblockIsReturnedFromParser()
    {
        $this->readerReturnsDocblock('/** @Before("here") */');
        $annotations = $this->parser->annotationsFor($this->testcase());
        $this->assertType('array', $annotations);
        $this->assertCount(1, $annotations);
    }

    private function readerReturnsDocblock($docblock)
    {
        $this->readerReturnsDocblocks([$docblock]);
    }

    private function readerReturnsDocblocks(array $docblocks)
    {
        $this->reader->shouldReceive('docblocksFor')
            ->with(\Mockery::type('Demeanor\\TestCase'))
            ->atLeast()
            ->times(1)
            ->andReturn($docblocks);
    }

    private function testcase()
    {
        return new TestCaseStub();
    }
}
