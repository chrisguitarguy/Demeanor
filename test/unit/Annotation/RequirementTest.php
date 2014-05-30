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

class RequirementTest extends AnnotationTestCase
{
    public function testContextThatDoesNotContainRequirementsDoesNothing()
    {
        $ctx = $this->testContextMock();
        $ctx->shouldReceive('offsetExists')
            ->once()
            ->with('requirements')
            ->andReturn(false);
        $annot = new Requirement([], []);

        $annot->attachRun($this->testCaseMock(), $ctx, $this->testResultMock());
    }

    public function testAnnotationWithCorrectArgumentsAddsRequirements()
    {
        $reqs = \Mockery::mock('Demeanor\\Requirement\\Requirements')->makePartial();
        $reqs->shouldReceive('add')
            ->atLeast(1)
            ->with(\Mockery::type('Demeanor\\Requirement\\Requirement'));
        $ctx = $this->testContextMock();
        $ctx->shouldReceive('offsetExists')
            ->once()
            ->with('requirements')
            ->andReturn(true);
        $ctx->shouldReceive('offsetGet')
            ->atLeast(1)
            ->andReturn($reqs);
        $annot = new Requirement([], [
            'php'       => '4.0',
            'os'        => '/darwin/ui',
            'extension' => 'spl',
        ]);

        $annot->attachRun($this->testCaseMock(), $ctx, $this->testResultMock());
    }
}
