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

/**
 * Resolves an annotation instance into an appropriate handler.
 *
 * @since   0.5
 */
interface HandlerResolver
{
    /**
     * Get the handler for a given annotation and test case. Handlers may change
     * depending on the testcase.
     *
     * @since   0.5
     * @param   Annotation $annotation The for which the handler will be found
     * @param   TestCase $testcase The test case on which the annotation will act
     * @return  string|null The string class name if present, or null if a handler
     *          is not found.
     */
    public function toHandler(Annotation $annotation, TestCase $testcase);
}
