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

namespace Demeanor\Finder;

use Demeanor\Exception\FileNotFoundException;

/**
 * Takes an array of files, checks to make sure they exist and returns them.
 *
 * @since   0.1
 */
class FileFinder implements Finder
{
    private $files = array();

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $files = array();
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                throw new FileNotFoundException("{$file} does not exist");
            }
            $files[] = realpath($file);
        }

        return $files;
    }
}
