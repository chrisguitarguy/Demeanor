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

use Counterpart\Assert;

class DiffReportTest extends ReportTestCase
{
    private $outputPath;
    private $report;

    public function __construct()
    {
        $this->outputPath = __DIR__.'/../../Fixtures/tmp/diff_report';
        if (is_dir($this->outputPath)) {
            $this->rmdir($this->outputPath);
        }

        $this->report = new DiffReport($this->outputPath);
    }

    public function testRenderGeneratesDirectoryOfDiffFiles()
    {
        list($coverage, $files) = $this->createCoverage();

        $this->report->render($coverage, $files);
        $rendered = glob($this->outputPath.'/*.diff');

        Assert::assertCount(1, $rendered);
        $lines = array_filter(file($rendered[0]), function ($line) {
            return 0 === strpos($line, '+');
        });
        Assert::assertCount(2, $lines);
    }
}
