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

namespace Demeanor\Loader;

use Demeanor\Exception\FileNotFoundException;

/**
 * Locates all files in a directory that match a specified suffix.
 *
 * @since   0.1
 */
class DirectoryLoader implements Loader
{
    const DEFAULT_SUFFIX = 'Test.php';

    private $directory;
    private $suffix;

    public function __construct($directory, $suffix=null)
    {
        $this->directory = $directory;
        $this->suffix = $suffix ?: self::DEFAULT_SUFFIX;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $files = array();
        foreach ($this->createIterator() as $file) {
            if ($file->getBasename($this->suffix) == $file->getBasename()) {
                continue;
            }

            $files[] = $file->getRealPath();
        }

        return $files;
    }

    private function createIterator()
    {
        try {
            return new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->directory),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
        } catch (\Exception $e) {
            throw new FileNotFoundException("Could not locate directory {$this->directory}", 0, $e);
        }
    }
}
