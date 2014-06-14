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

namespace Demeanor\Coverage;

use Demeanor\TestCase;
use Demeanor\Finder\Finder;
use Demeanor\Coverage\Driver\Driver;
use Demeanor\Coverage\Driver\DriverFactory;

/**
 * Collects coverage on test cases.
 *
 * @since   0.3
 */
class Collector implements \IteratorAggregate
{
    /**
     * A driver instance that powers the code coverage.
     *
     * @since   0.3
     * @var     Driver
     */
    private $driver;

    /**
     * The collection of code coverage.
     *
     * @since   0.3
     * @var     ArrayObject
     */
    private $coverage;

    /**
     * Constructor. Set up the finder and, optionally, the driver. If no driver
     * is supplied `DriverFactory` will be used to create one that makes sense
     * for the runtime.
     *
     * @since   0.3
     * @param   Finder $allowedFinder
     * @param   Driver $driver
     */
    public function __construct(Driver $driver=null)
    {
        $this->driver = $driver ?: DriverFactory::create();
        $this->coverage = new \ArrayObject();
    }

    /**
     * Being the evaluation on a test case.
     *
     * @since   0.3
     * @param   TestCase $testcase
     * @return  void
     */
    public function start(TestCase $testcase)
    {
        $this->driver->start();
    }

    /**
     * Complete a test case, adding the files found in code coverage to the
     * report storage.
     *
     * @since   0.3
     * @param   TestCase $testcase
     * @return  void
     */
    public function finish(TestCase $testcase)
    {
        $covered = $this->driver->finish();
        foreach ($covered as $file => $lines) {
            $this->addCoveredLines($testcase, $file, $lines);
        }
    }

    public function getIterator()
    {
        return $this->coverage;
    }

    private function addCoveredLines(TestCase $testcase, $filename, array $lines)
    {
        if (!file_exists($filename)) {
            return false;
        }

        foreach ($lines as $lineno) {
            $this->addCoveredLine($testcase, realpath($filename), $lineno);
        }
    }

    private function addCoveredLine(TestCase $testcase, $filename, $lineno)
    {
        if (!isset($this->coverage[$filename])) {
            $this->coverage[$filename] = new \ArrayObject();
        }

        if (!isset($this->coverage[$filename][$lineno])) {
            $this->coverage[$filename][$lineno] = array();
        }

        $this->coverage[$filename][$lineno][] = $testcase;
    }
}
