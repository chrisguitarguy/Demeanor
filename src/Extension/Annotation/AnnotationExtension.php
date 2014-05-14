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

    public function __construct(AnnotationCollectionInterface $collection=null, ParserInterface $parser=null)
    {
        $this->collection = $collection ?: $this->createCollection();
        $this->parser = $parser ?: new Parser();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::BEFORE_TESTCASE => 'parseAnnotation',
        ];
    }

    public function parseAnnotation(TestRunEvent $event)
    {
        $testcase = $event->getTestCase();
        if (!$testcase instanceof UnitTestCase) {
            return;
        }

        $docblock = $testcase->getReflectionMethod()->getDocComment();
        if (!$docblock) {
            return;
        }

        try {
            $annotations = $this->parser->parse($docblock);
        } catch (\Exception $e) {
            return;
        }

        if (!$annotations) {
            return;
        }

        $results = array();
        $ctx = ['method' => $testcase->getReflectionMethod()];
        foreach ($annotations as $annotation) {
            list($name, $arguments) = $annotation;
            if ($anot = $this->collection->create($name, $arguments, $ctx)) {
                $results[] = $anot;
            }
        }


        $context = $event->getTestContext();
        $result = $event->getTestResult();
        foreach ($results as $anot) {
            $anot->attach($testcase, $context, $result);
        }
    }

    private function createCollection()
    {
        return new AnnotationCollection([
            'Before'    => __NAMESPACE__ . '\\Before',
            'After'     => __NAMESPACE__ . '\\After',
            'Expect'    => __NAMESPACE__ . '\\Expect',
        ]);
    }
}
