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
 * The default implementation of handler resolver. Turns an annotation into:
 *
 *      {AnnotationClassName}{TestType}Handler
 *
 * So the `Demeanor\Annotation\Before` class would be converted to the handler
 * `Demeanor\Annotation\BeforeUnitTestCaseHandler` for `UnitTestCase` instances.
 *
 * If the specific test type handler isn't found, this will look for:
 *
 *      {AnnotationClassName}Handler
 *
 * @since   0.5
 */
class DefaultHandlerResolver implements HandlerResolver
{
    /**
     * {@inheritdoc}
     */
    public function toHandler(Annotation $annotation, TestCase $testcase)
    {
        if ($this->isHandlerAware($annotation)) {
            return $annotation->handledBy($testcase);
        }

        $typeHandler = $this->toTypeHandler($annotation, $testcase);

        return $typeHandler ? $typeHandler : $this->toCommonHandler($annotation, $testcase);
    }

    private function isHandlerAware(Annotation $annotation)
    {
        return $annotation instanceof HandlerAware;
    }

    private function toTypeHandler(Annotation $annotation, TestCase $testcase)
    {
        $ref = new \ReflectionClass($testcase);
        $handler = sprintf('%s%sHandler', get_class($annotation), $ref->getShortName());
        return $this->validateHandler($handler);
    }

    private function toCommonHandler(Annotation $annotation, TestCase $testcase)
    {
        $handler = sprintf('%sHandler', get_class($annotation));
        return $this->validateHandler($handler);
    }

    private function validateHandler($handler)
    {
        try {
            $ref = new \ReflectionClass($handler);
        } catch (\ReflectionException $e) {
            return null;
        }

        return $ref->implementsInterface('Demeanor\\Annotation\\Handler') ? $handler : null;
    }
}
