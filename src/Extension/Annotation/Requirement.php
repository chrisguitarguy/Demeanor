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

namespace Demeanor\Extension\Annotation;

use Demeanor\TestContext;
use Demeanor\TestResult;
use Demeanor\Unit\UnitTestCase;
use Demeanor\Extension\Requirement\VersionRequirement;
use Demeanor\Extension\Requirement\RegexRequirement;
use Demeanor\Extension\Requirement\ExtensionRequirement;

/**
 * Set the expected exeception for the test case.
 *
 * @since   0.1
 */
class Requirement extends Annotation
{
    /**
     * {@inheritdoc}
     */
    public function attachRun(UnitTestCase $testcase, TestContext $context, TestResult $result)
    {
        if (!isset($context['requirements'])) {
            return;
        }

        if (isset($this->args['php'])) {
            $context['requirements']->add(new VersionRequirement($this->args['php']));
        }

        if (isset($this->args['os'])) {
            $context['requirements']->add(new RegexRequirement($this->args['os'], PHP_OS, 'operating system'));
        }

        if (isset($this->args['extension'])) {
            $context['requirements']->add(new ExtensionRequirement($this->args['extension']));
        }
    }
}
