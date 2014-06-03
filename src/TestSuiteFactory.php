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

namespace Demeanor;

use Demeanor\Finder\ChainFinder;
use Demeanor\Finder\DirectoryFinder;
use Demeanor\Finder\FileFinder;
use Demeanor\Finder\GlobFinder;
use Demeanor\Finder\ExcludingFinder;
use Demeanor\Unit\UnitTestSuite;
use Demeanor\Spec\SpecTestSuite;
use Demeanor\Phpt\PhptTestSuite;
use Demeanor\Exception\InvalidTestSuiteException;

/**
 * Builds test suites from a given configuration.
 *
 * @since   0.1
 */
class TestSuiteFactory
{
    const TYPE_UNIT     = 'unit';
    const TYPE_SPEC     = 'spec';
    const TYPE_STORY    = 'story';
    const TYPE_PHPT     = 'phpt';

    public function create($name, array $configuration)
    {
        switch ($configuration['type']) {
            case self::TYPE_UNIT:
                $suite = $this->createUnitTestSuite($name, $configuration);
                break;
            case self::TYPE_SPEC:
                $suite = $this->createSpecTestSuite($name, $configuration);
                break;
            case self::TYPE_PHPT:
                $suite = $this->createPhptTestSuite($name, $configuration);
                break;
            default:
                throw new InvalidTestSuiteException("{$configuration['type']} is not a valid test suite type");
                break;
        }

        return $suite;
    }

    private function createUnitTestSuite($name, array $configuration)
    {
        $finder = $this->createFinder($configuration);
        return new UnitTestSuite($name, $finder, $configuration['bootstrap']);
    }

    private function createSpecTestSuite($name, array $configuration)
    {
        $finder = $this->createFinder($configuration, 'spec.php');
        return new SpecTestSuite($name, $finder, $configuration['bootstrap']);
    }

    private function createPhptTestSuite($name, array $configuration)
    {
        $finder = $this->createFinder($configuration, '.phpt');
        return new PhptTestSuite($name, $finder, $configuration['bootstrap']);
    }

    private function createFinder(array $configuration, $suffix=null)
    {
        $finder = new ChainFinder();
        $this->addDirectoryFinders($finder, $configuration['directories'], $suffix);
        $this->addFileFinders($finder, $configuration['files']);
        $this->addGlobFinders($finder, $configuration['glob']);

        $exclude = new ChainFinder();
        $this->addDirectoryFinders($exclude, $configuration['exclude']['directories'], $suffix);
        $this->addFileFinders($exclude, $configuration['exclude']['files']);
        $this->addGlobFinders($exclude, $configuration['exclude']['glob']);

        return new ExcludingFinder($finder, $exclude);
    }

    private function addDirectoryFinders(ChainFinder $chain, array $directories, $suffix=null)
    {
        if (!$directories) {
            return;
        }

        foreach ($directories as $directory) {
            $chain->addFinder(new DirectoryFinder($directory, $suffix));
        }
    }

    private function addFileFinders(ChainFinder $chain, array $files)
    {
        if (!$files) {
            return;
        }

        $chain->addFinder(new FileFinder($files));
    }

    private function addGlobFinders(ChainFinder $chain, array $globs)
    {
        if (!$globs) {
            return;
        }

        foreach ($globs as $glob) {
            $chain->addFinder(new GlobFinder($glob));
        }
    }
}
