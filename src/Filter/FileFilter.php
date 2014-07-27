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

namespace Demeanor\Filter;

use Demeanor\TestCase;
use Demeanor\Exception\InvalidArgumentException;

/**
 * Checks a test case's file name against a supplied file name. If they match,
 * the testcase can runn
 *
 * @since   0.4
 */
class FileFilter implements Filter
{
    private $filename;

    /**
     * Constructor: set up the directory.
     *
     * @since   0.4
     * @param   string $path
     * @throws  Demeanor\Exception\InvalidArgumentException if the path supplied
     *          doesn't exist or isn't a file
     * @return  void
     */
    public function __construct($path)
    {
        $_path = realpath($path);
        if (false === $_path || !is_file($_path)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid file', $path));
        }

        $this->filename = $_path;
    }

    /**
     * {@inheritdoc}
     */
    public function canRun(TestCase $testcase)
    {
        return $testcase->filename() === $this->filename;
    }
}
