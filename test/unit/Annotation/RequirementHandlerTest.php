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
use Demeanor\Requirement\Requirements;
use Demeanor\Requirement\RequirementsStorage;

class RequirementHandlerTest extends CallbackTestCase
{
    use \Counterpart\Assert;

    private $handler, $storage;

    public function __construct()
    {
        $this->storage = new RequirementsStorage();
        $this->handler = new RequirementHandler($this->storage);
        $this->testcase = new TestCaseStub();
        $this->reqs = \Mockery::mock('Demeanor\\Requirement\\Requirements');
        $this->storage->set($this->testcase, $this->reqs);
    }

    public function testPhpRequirementAddsNewVersionRequirement()
    {
        $this->willAdd('Demeanor\\Requirement\\VersionRequirement');
        $this->handler->onSetup(new Requirement([], ['php' => '5.0']), $this->testcase);
    }

    public function testOsRequirementAddsNewRegexRequirement()
    {
        $this->willAdd('Demeanor\\Requirement\\RegexRequirement');
        $this->handler->onSetup(new Requirement([], ['os' => 'darwin']), $this->testcase);
    }

    public function testExtensionRequirementsAddsNewExtensionRequirement()
    {
        $this->willAdd('Demeanor\\Requirement\\ExtensionRequirement');
        $this->handler->onSetup(new Requirement([], ['extension' => 'spl']), $this->testcase);
    }

    private function willAdd($class)
    {
        $this->reqs->shouldReceive('add')
            ->with(\Mockery::type($class))
            ->atLeast(1);
    }
}
