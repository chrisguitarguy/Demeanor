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

namespace Demeanor\Phpt;

use Demeanor\AbstractTestCase;
use Demeanor\TestResult;
use Demeanor\TestContext;
use Demeanor\Exception\UnexpectedValueException;

class PhptTestCase extends AbstractTestCase
{
    private $filename;
    private $parser;
    private $sections = null;
    private $name = null;

    public function __construct($filename, Parser $parser=null)
    {
        $this->filename = $filename;
        $this->parser = $parser ?: new Parser();
    }

    /**
     * {@inheritdoc}
     * Note: phpt tests don't use `$testArgs`
     */
    protected function doRun(array $testArgs)
    {
        $testCode = $this->getSection('FILE');
    }

    /**
     * {@inheritdoc}
     */
    protected function generateName()
    {
        if (null === $this->name) {
            try {
                $name = trim($this->getSection('TEST'));
            } catch (\Exception $e) {
                $name = '';
            }

            $this->name = sprintf('[%s] %s', basename($this->filename), $name);
        }

        return $this->name;
    }

    private function parse()
    {
        if (null !== $this->sections) {
            return;
        }

        $this->sections = $this->parser->parse(new \SplFileObject($this->filename));

        if (!isset($this->sections['TEST'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains a --TEST-- section");
        }

        if (!isset($this->sections['FILE'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains a --FILE-- section");
        }

        if (!isset($this->section['EXPECT']) && !isset($this->sections['EXPECTF'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains an --EXPECT-- or --EXPECTF-- section");
        }
    }

    private function getSection($section)
    {
        $this->parse();
        return isset($this->sections[$section]) ? $this->sections[$section] : null;
    }
}
