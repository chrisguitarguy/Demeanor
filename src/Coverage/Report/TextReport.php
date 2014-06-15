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

/**
 * Generates a single text file with filename % covered pairs
 *
 * @since   0.3
 */
class TextReport implements Report
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function render(\ArrayAccess $coverage, array $files)
    {
        $fh = $this->openFile();
        foreach ($files as $file) {
            $this->addReportLine($fh, $coverage, $file);
        }
        $fh->fflush();
    }

    private function addReportLine(\SplFileObject $fh, \ArrayAccess $coverage, $file)
    {
        $covered = isset($coverage[$file]) ? $coverage[$file] : array();
        $percent = 100 * (count($covered)/count(file($file)));
        $fh->fwrite(sprintf("%s %.3f%%\n", $file, $percent));
    }

    private function openFile()
    {
        $filename = $this->normalizeFilename();
        return new \SplFileObject($filename, 'w');
    }

    private function normalizeFilename()
    {
        switch ($this->filename) {
            case 'STDOUT':
                return 'php://stdout';
                break;
            case 'STDERR':
                return 'php://stderr';
                break;
            default:
                return $this->filename;
                break;
        }
    }
}
