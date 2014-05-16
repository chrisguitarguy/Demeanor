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

use Chrisguitarguy\Annotation\Parser;
use Chrisguitarguy\Annotation\ParserInterface;
use Chrisguitarguy\Annotation\AnnotationCollection;
use Chrisguitarguy\Annotation\AnnotationCollectionInterface;
use Demeanor\Events;
use Demeanor\Event\Subscriber;
use Demeanor\Event\TestRunEvent;
use Demeanor\Unit\UnitTestCase;

class AnnotationExtension implements Subscriber
{
    private $parser;
    private $collection;
    private $cache;

    public function __construct(AnnotationCollectionInterface $collection=null, ParserInterface $parser=null)
    {
        $this->collection = $collection ?: $this->createCollection();
        $this->parser = $parser ?: new Parser();
        $this->cache = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::BEFORE_TESTCASE => 'attachRun',
        ];
    }

    public function attachRun(TestRunEvent $event)
    {
        $testcase = $event->getTestCase();
        if (!$testcase instanceof UnitTestCase) {
            return;
        }

        $annotations = $this->testCaseAnnotations($testcase);

        $context = $event->getTestContext();
        $result = $event->getTestResult();
        foreach ($annotations as $annot) {
            $annot->attachRun($testcase, $context, $result);
        }
    }

    private function testCaseAnnotations(UnitTestCase $testcase)
    {
        if (!isset($this->cache[$testcase])) {
            $toParse = [
                'method'    => $testcase->getReflectionMethod(),
                'class'     => $testcase->getReflectionClass(),
            ];

            $annotations = array();
            foreach ($toParse as $ctxName => $ref) {
                $annotations = array_merge($annotations, $this->parseDocblock($ref->getDocComment(), [
                    $ctxName => $ref,
                ]));
            }

            $this->cache[$testcase] = $annotations;
        }

        return $this->cache[$testcase];
    }

    private function parseDocblock($docblock, array $colContext)
    {
        $annotations = array();
        if (!$docblock) {
            return $annotations;
        }

        try {
            $found = $this->parser->parse($docblock);
        } catch (\Exception $e) {
            return $annotations;
        }

        foreach ($found as $foundAnnotation) {
            list($name, $arguments) = $foundAnnotation;
            if ($annot = $this->collection->create($name, $arguments, $colContext)) {
                $annotations[] = $annot;
            }
        }

        return $annotations;
    }

    private function createCollection()
    {
        return new AnnotationCollection([
            'Before'    => __NAMESPACE__ . '\\Before',
            'After'     => __NAMESPACE__ . '\\After',
            'Expect'    => __NAMESPACE__ . '\\Expect',
            'Require'   => __NAMESPACE__ . '\\Requirement',
        ]);
    }
}
