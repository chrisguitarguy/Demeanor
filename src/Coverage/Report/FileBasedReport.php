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

namespace Demeanor\Coverage\Report;

use Demeanor\Exception\InvalidArgumentException;

/**
 * A base class for reports that write information about individual source files.
 * This provides a few utilities for generating files names as well as a constructor
 * that ensures the output directory exists.
 *
 * @since   0.3
 */
abstract class FileBasedReport implements Report
{
    private $outputDirectory;

    /**
     * Constructor. Set up the output directory. If it doesn't exist, an attempt
     * will be made to create it.
     *
     * @since   0.3
     * @param   string $outputDirectory
     * @return  void
     */
    public function __construct($outputDirectory)
    {
        if (!is_dir($outputDirectory) && !$this->makeDirectory($outputDirectory)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid directory',
                $outputDirectory
            ));
        }
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * Create a normalized output file name for the report.
     *
     * @since   0.3
     * @param   string $originalFile
     * @param   string $extension
     * @return  string
     */
    protected function createOutputFilename($originalFile, $extension)
    {
        $filePath = str_replace(
            ['\\', '/'],
            '_',
            trim(str_replace(getcwd(), '', $originalFile), '/\\')
        );

        return $this->pathJoin($this->outputDirectory, $filePath.$extension);
    }

    private function makeDirectory($outputDirectory)
    {
        return !file_exists($outputDirectory) && @mkdir($outputDirectory);
    }

    private function pathJoin()
    {
        return implode(DIRECTORY_SEPARATOR, array_map(function ($part) {
            return rtrim($part, DIRECTORY_SEPARATOR);
        }, func_get_args()));
    }
}
