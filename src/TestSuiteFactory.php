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
use Demeanor\Unit\UnitTestSuite;
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

    public function create($name, array $configuration)
    {
        $configuration = $this->setDefaults($configuration);

        switch ($configuration['type']) {
            case self::TYPE_UNIT:
                $suite = $this->createUnitTestSuite($name, $configuration);
                break;
            default:
                throw new InvalidTestSuiteException("{$configuration['type']} is not a valid test suite type");
                break;
        }

        return $suite;
    }

    private function setDefaults(array $configuration)
    {
        return array_replace([
            'type'          => self::TYPE_UNIT,
            'directories'   => array(),
            'files'         => array(),
            'glob'          => array(),
            'bootstrap'     => array(),
        ], $configuration);
    }

    private function createUnitTestSuite($name, array $configuration)
    {
        $loader = new ChainLoader();
        $this->addDirectoryLoaders($loader, $configuration['directories']);

        return new UnitTestSuite($name, $loader, $configuration['bootstrap']);
    }

    private function addDirectoryLoaders(ChainLoader $chain, array $directories, $suffix=null)
    {
        foreach ($directories as $directory) {
            $chain->addLoader(new DirectoryLoader($directory, $suffix));
        }
    }
}
