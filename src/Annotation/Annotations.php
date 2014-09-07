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
 * A Facade around the entire annotation interface.
 *
 * @since   0.5
 */
class Annotations
{
    /**
     * @since   0.5
     * @var     HandlerResolver
     */
    private $handlerResolver = null;

    /**
     * @since   0.5
     * @var     Parser\TestCaseParser
     */
    private $testCaseParser = null;

    public function onRun(TestCase $testcase, TestResult $result)
    {
        $handlers = $this->toHandlers($testcase);
        foreach ($handlers as $handler => $annotation) {
            $h = new $handler();
            $h->onRun($annotation, $testcase, $result);
        }
    }

    public function onSetup(TestCase $testcase)
    {
        $handlers = $this->toHandlers($testcase);
        foreach ($handlers as $handler => $annotation) {
            $h = new $handler();
            $h->onSetup($annotation, $testcase);
        }
    }

    private function toHandlers(TestCase $testcase)
    {
        $out = array();
        foreach ($this->getParser()->annotationsFor($testcase) as $annot) {
            $handler = $this->getResolver()->toHandler($annot, $testcase);
            if (!$handler) {
                continue;
            }

            $out[$handler] = $annot;
        }

        return $out;
    }

    private function getParser()
    {
        if (null === $this->testCaseParser) {
            $this->testCaseParser = $this->createParser();
        }

        return $this->testCaseParser;
    }

    private function getResolver()
    {
        if (null === $this->handlerResolver) {
            $this->handlerResolver = $this->createResolver();
        }

        return $this->handlerResolver;
    }

    private function createParser()
    {
        return new Parser\CachingTestCaseParser(
            new Parser\DefaultTestCaseParser(new Reader\ChainReader([
                new Reader\UnitTestCaseReader(),
            ]))
        );
    }

    private function createResolver()
    {
        return new DefaultHandlerResolver();
    }
}
