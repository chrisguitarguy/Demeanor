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

use Demeanor\Loader\ChainLoader;
use Demeanor\Loader\DirectoryLoader;
use Demeanor\Loader\FileLoader;
use Demeanor\Loader\GlobLoader;
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
            case self:TYPE_PHPT:
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
        $loader = $this->createLoader($configuration);
        return new UnitTestSuite($name, $loader, $configuration['bootstrap']);
    }

    private function createSpecTestSuite($name, array $configuration)
    {
        $loader = $this->createLoader($configuration, 'spec.php');
        return new SpecTestSuite($name, $loader, $configuration['bootstrap']);
    }

    private function createPhptTestSuite($name, array $configuration)
    {
        $loader = $this->createLoader($configuration, '.phpt');
        return new PhptTestSuite($name, $loader, $configuration['bootstrap']);
    }

    private function createLoader(array $configuration, $suffix=null)
    {
        $loader = new ChainLoader();
        $this->addDirectoryLoaders($loader, $configuration['directories'], $suffix);
        $this->addFileLoaders($loader, $configuration['files']);
        $this->addGlobLoaders($loader, $configuration['glob']);

        return $loader;
    }

    private function addDirectoryLoaders(ChainLoader $chain, array $directories, $suffix=null)
    {
        if (!$directories) {
            return;
        }

        foreach ($directories as $directory) {
            $chain->addLoader(new DirectoryLoader($directory, $suffix));
        }
    }

    private function addFileLoaders(ChainLoader $chain, array $files)
    {
        if (!$files) {
            return;
        }

        $chain->addLoader(new FileLoader($files));
    }

    private function addGlobLoaders(ChainLoader $chain, array $globs)
    {
        if (!$globs) {
            return;
        }

        foreach ($globs as $glob) {
            $chain->addLoader(new GlobLoader($glob));
        }
    }
}
