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

/**
 * A utility to build ChainFilter instances.
 *
 * @since   0.3
 */
class FinderBuilder
{
    private $finders = array();

    public static function create()
    {
        return new static();
    }

    public function withDirectory($directory, $suffix=null)
    {
        $this->finders[] = new DirectoryFinder($directory, $suffix);
        return $this;
    }

    public function withDirectories(array $directories, $suffix=null)
    {
        foreach ($directories as $directory) {
            $this->withDirectory($directory, $suffix);
        }
        return $this;
    }

    public function withFiles(array $files)
    {
        $this->finders[] = new FileFinder($files);
        return $this;
    }

    public function withGlob($pattern)
    {
        $this->finders[] = new GlobFinder($pattern);
        return $this;
    }

    public function withGlobs(array $patterns)
    {
        foreach ($patterns as $pattern) {
            $this->withGlob($pattern);
        }
        return $this;
    }

    public function build()
    {
        return new ChainFinder($this->finders);
    }
}
