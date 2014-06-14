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
use Demeanor\Coverage\Collector;

/**
 * Render coverage as "diff" files with +'s for covered lines and -'s for
 * uncovered. Diff reports don't include information about what test cases
 * covered what.
 *
 * @since   0.3
 */
class DiffReport extends FileBasedReport
{
    /**
     * {@inheritdoc}
     */
    public function render(\ArrayAccess $coverage, array $fileWhitelist)
    {
        foreach ($fileWhitelist as $file) {
            $this->renderFile($coverage, $file);
        }
    }

    private function renderFile(\ArrayAccess $coverage, $file)
    {
        $filePath = $this->createOutputFilename($file, '.diff');

        $covered = isset($collector[$file]) ? $coverage[$file] : array();

        $lines = file($file);
        $fh = new \SplFileObject($filePath, 'w');
        $fh->fwrite('# '.$file."\n");
        $fh->fwrite(sprintf("# %.3f%% Covered\n", 100 * (count($covered)/count($lines))));
        foreach ($lines as $lineno => $line) {
            $prefix = isset($covered[$lineno+1]) ? '+' : '-';
            $fh->fwrite($prefix.$line);
        }
        $fh->fflush();
    }
}
