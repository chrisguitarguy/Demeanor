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

namespace Demeanor\Annotation\Parser;

use Chrisguitarguy\Annotation\Parser;
use Chrisguitarguy\Annotation\ParserInterface;
use Chrisguitarguy\Annotation\AnnotationCollection;
use Chrisguitarguy\Annotation\AnnotationCollectionInterface;
use Demeanor\TestCase;
use Demeanor\Annotation\Reader\DocblockReader;

/**
 * Default implementation of TestCaseParser.
 *
 * @since   0.5
 */
class DefaultTestCaseParser implements TestCaseParser
{
    /**
     * @since   0.5
     * @var     ParserInterface
     */
    private $parser;

    /**
     * @since   0.5
     * @var     DocblockReader
     */
    private $reader;

    /**
     * @since   0.5
     * @var     AnnotationCollectionInterface
     */
    private $annotations;

    /**
     * Set up the parser and reader.
     *
     * @since   0.5
     * @param   DocblockReader $reader
     * @param   ParserInterface|null $parser
     * @param   AnnotationCollectionInterface $collection
     * @return  void
     */
    public function __construct(
        DocblockReader $reader,
        ParserInterface $parser=null,
        AnnotationCollectionInterface $collection=null
    ) {
        $this->reader = $reader;
        $this->parser = $parser ?: new Parser();
        $this->annotations = $collection ?: $this->createDefaultCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function annotationsFor(TestCase $testcase)
    {
        $docblocks = $this->reader->docblocksFor($testcase);
        $annotations = array();
        foreach ($docblocks as $docblock) {
            $annotations = array_merge($annotations, $this->parseAnnotations($docblock));
        }

        return $this->annotationsToObjects($annotations);
    }

    private function parseAnnotations($docblock)
    {
        try {
            return $this->parser->parse($docblock);
        } catch (\Exception $e) {
            return array();
        }
    }

    private function annotationsToObjects(array $annotations)
    {
        $out = array();
        foreach ($annotations as $annotation) {
            $out[] = $this->createAnnotation($annotation);
        }

        return array_filter($out);
    }

    private function createAnnotation(array $annotation)
    {
        list($name, $positional, $named) = $annotation;
        return $this->annotations->create($name, $positional, $named, []);
    }

    private function createDefaultCollection()
    {
        $ns = 'Demeanor\\Annotation\\';
        return new AnnotationCollection([
            'Before'    => "{$ns}Before",
            'After'     => "{$ns}After",
            'Expect'    => "{$ns}Expect",
            'Require'   => "{$ns}Requirement",
            'Provider'  => "{$ns}DataProvider",
            'Group'     => "{$ns}Group",
        ]);
    }
}
