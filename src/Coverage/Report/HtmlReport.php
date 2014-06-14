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
 * Renders HTML files with coverage reports.
 *
 * @since   0.3
 */
class HtmlReport extends FileBasedReport
{
    /**
     * {@inheritdoc}
     */
    public function render(\ArrayAccess $coverage, array $files)
    {
        natcasesort($files);
        $index = array();
        foreach ($files as $file) {
            $index[$file] = $this->renderFile($coverage, $file);
        }
        $content = $this->renderTemplate(__DIR__.'/html_templates/index.php', [
            'files'     => $index,
        ]);
        $fh = new \SplFileObject($this->createOutputFilename('index', '.html'), 'w');
        $fh->fwrite($content);
        $fh->fflush();
    }

    private function renderFile(\ArrayAccess $coverage, $file)
    {
        $outputPath = $this->createOutputFilename($file, '.html');
        $lines = file($file);
        $covered = isset($coverage[$file]) ? $coverage[$file] : array();
        $coveredPercent = 100 * (count($covered)/count($lines));

        $content = $this->renderTemplate(__DIR__.'/html_templates/single.php', [
            'filename'          => $file,
            'lines'             => $lines,
            'covered'           => $covered,
            'coveredPercent'    => $coveredPercent,
        ]);
        $fh = new \SplFileObject($outputPath, 'w');
        $fh->fwrite($content);
        $fh->fflush();

        return [basename($outputPath), $coveredPercent];
    }

    private function renderTemplate($templateFile, array $context)
    {
        extract($context);
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }
}
