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

/**
 * Set the expected exeception for the test case.
 *
 * @since   0.1
 */
class Expect extends Annotation
{
    /**
     * {@inheritdoc}
     */
    public function attach(UnitTestCase $testcase, TestContext $context, TestResult $result)
    {
        if (!isset($this->args['exception'])) {
            return;
        }

        $this->args['exception'] = $this->normalizeName($this->args['exception']);

        if (
            !class_exists($this->args['exception']) &&
            !interface_exists($this->args['exception'])
        ) {
            $result->error();
            $result->addMessage('error', sprintf(
                'Expected exception class "%s" does not exist',
                $this->args['exception']
            ));
            return;
        }

        $testcase->willThrow($this->args['exception']);
    }
}
