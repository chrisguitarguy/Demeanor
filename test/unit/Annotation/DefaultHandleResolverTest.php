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

use Demeanor\TestCaseStub;

class DefaultHandlerResolverTest
{
    use \Counterpart\Assert;

    private $resolver;
    private $testcase;

    public function __construct()
    {
        $this->resolver = new DefaultHandlerResolver();
        $this->testcase = new TestCaseStub();
    }

    public function testHandlerAwareReturnsHandlerDirectlyFromAnnotation()
    {
        $annot = new HandlerAwareStub('AHandler');

        $this->assertEquals('AHandler', $this->resolver->toHandler($annot, $this->testcase));
    }

    public function testTestTypeSpecificHandlerIsReturnedWhenFound()
    {
        $annot = new AnnotationStub();

        $this->assertEquals(
            'Demeanor\\Annotation\\AnnotationStubTestCaseStubHandler',
            $this->resolver->toHandler($annot, $this->testcase)
        );
    }

    public function testCommonHandlerIsReturnedWhenFound()
    {
        $annot = new NoSpecificHandlerStub();

        $this->assertEquals(
            'Demeanor\\Annotation\\NoSpecificHandlerStubHandler',
            $this->resolver->toHandler($annot, $this->testcase)
        );
    }

    public function testNoHandlerIsReturnedWhenOneCannotBeFound()
    {
        $annot = \Mockery::mock('Demeanor\\Annotation\\Annotation');

        $this->assertNull($this->resolver->toHandler($annot, $this->testcase));
    }
}
