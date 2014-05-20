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

use Counterpart\Assert;
use Demeanor\AbstractTestCase;
use Demeanor\Exception\UnexpectedValueException;
use Demeanor\Exception\TestSkipped;

class PhptTestCase extends AbstractTestCase
{
    private $filename;
    private $parser;
    private $executor;
    private $sections = null;
    private $name = null;

    public function __construct($filename, Executor $executor, Parser $parser=null)
    {
        $this->filename = $filename;
        $this->executor = $executor;
        $this->parser = $parser ?: new Parser();
    }

    /**
     * {@inheritdoc}
     * Note: phpt tests don't use `$testArgs`
     */
    protected function doRun(array $testArgs)
    {
        $testCode = $this->getSection('FILE');
        $skipCode = $this->getSection('SKIPIF');
        $cleanCode = $this->getSection('CLEAN');

        if ($skipReason = $this->shouldSkip($skipCode)) {
            throw new TestSkipped($skipReason);
        }

        list($stdout, $stderr) = $this->runCode($testCode);

        $stdout = trim($stdout);
        $thrown = null;
        try {
            $this->checkResult($stdout);
        } catch (\Exception $thrown) {
            // we'll deal with thrown below
        }

        if ($clean = $this->getSection('CLEAN')) {
            $this->runCode($clean);
        }

        if ($thrown) {
            throw $thrown;
        }
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

        if (empty($this->sections['TEST'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains a --TEST-- section");
        }

        if (empty($this->sections['FILE'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains a --FILE-- section");
        }

        if (empty($this->sections['EXPECT']) && empty($this->sections['EXPECTF'])) {
            throw new UnexpectedValueException("{$this->filename} does not contains an --EXPECT-- or --EXPECTF-- section");
        }
    }

    private function getSection($section)
    {
        $this->parse();
        return isset($this->sections[$section]) ? $this->sections[$section] : null;
    }

    private function shouldSkip($skipCode)
    {
        if (!$skipCode) {
            return false;
        }

        list($stdout, $stderr) = $this->runCode($skipCode);
        if (preg_match('/^skip(.*)$/ui', $stdout, $matches)) {
            return trim($matches[1]);
        }

        return false;
    }

    /**
     * Put $code into a file and run it.
     *
     * @since   0.1
     * @param   string $code
     * @return  array|false [$stdout, $stderr]
     */
    private function runCode($code)
    {
        $code = str_replace(
            ['__DIR__', '__FILE__'],
            ['"'.dirname($this->filename).'"', '"'.$this->filename.'"'],
            $code
        );

        return $this->executor->execute($code, $this->getSection('ENV') ?: array());
    }

    /**
     * Compare the result to either the `EXPECTF` or `EXPECT` sections.
     *
     * @since   0.1
     * @param   string $stdout The standard output from the process
     * @return  void No throw means success
     */
    private function checkResult($stdout)
    {
        if ($expectf = $this->getSection('EXPECTF')) {
            Assert::assertMatchesPhptFormat($expectf, $stdout);
        } else {
            Assert::assertEquals($this->getSection('EXPECT'), $stdout);
        }
    }
}
