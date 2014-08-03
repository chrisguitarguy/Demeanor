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

use Demeanor\TestCase;
use Demeanor\TestResult;

/**
 * Handler for the expect annotation.
 *
 * @since   0.5
 */
class ExpectHandler extends AbstractHandler
{
    use NameNormalizationTrait;

    /**
     * {@inheritdoc}
     */
    public function onRun(Annotation $annotation, TestCase $testcase, TestResult $result)
    {
        $class = $annotation->positional(0);
        if (!$class) {
            $class = $annotation->named('exception');
        }

        if (!$class) {
            return;
        }

        $class = $this->normalizeName($class);
        if (!class_exists($class) && !interface_exists($class)) {
            $result->error();
            $result->addMessage('error', sprintf('Expected exception "%s" does not exist', $class));
            return;
        }

        $testcase->willThrow($class);
    }
}
