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
 * Render coverage as "diff" files. Diff reports don't include information
 * about what test cases covered what.
 *
 * @since   0.3
 */
class DiffReport implements Report
{
    private $outputPath;

    public function __construct($outputPath)
    {
        if (!is_dir($outputPath)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid directory',
                $outputPath
            ));
        }

        $this->outputPath = $outputPath;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Collector $collector, array $fileWhitelist)
    {
        $iter = $collector->getIterator();
        $cwd = getcwd();
        foreach ($fileWhitelist as $file) {
            $this->renderFile($iter, $cwd, $file);
        }
    }

    private function renderFile(\ArrayAccess $collector, $cwd, $file)
    {
        $dir = dirname($file);
        $filePath = $this->outputPath.DIRECTORY_SEPARATOR.str_replace(
            ['\\', '/'],
            '_',
            trim(str_replace($cwd, '', $file), '/\\')
        ).'.diff';

        $covered = isset($collector[$file]) ? $collector[$file] : array();

        $lines = file($file);
        $fh = fopen($filePath, 'w');
        fwrite($fh, '# '.$file."\n");
        foreach ($lines as $lineno => $line) {
            $prefix = isset($covered[$line+1]) ? '+' : '-';
            fwrite($fh, $prefix.$line);
        }
        fclose($fh);
    }
}
