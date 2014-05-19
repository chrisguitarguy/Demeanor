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
        $skipCode = $this->getSection('SKIPIF');
        $cleanCode = $this->getSection('CLEAN');
        $expectCode = $this->getSection('EXPECTF') ?: $this->getSection('EXPECT');

        if ($skipReason = $this->shouldSkip($skipCode)) {
            return $testArgs[0]->skip($skipReason); // $args[0] is ALWAYS the TestContext
        }

        list($stdout, $stderr) = $this->runCode($testCode);

        // todo actually test stuff
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
        if (preg_match('/^skip\s+(.*)$/ui', $stdout, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Put $code into a file and run it.
     *
     * TODO this should probably be it's own class
     *
     * @since   0.1
     * @param   string $code
     * @return  array|false [$stdout, $stderr]
     */
    private function runCode($code)
    {
        $env = $this->getSection('ENV') ?: null;

        $proc = proc_open(PHP_BINARY, [
            0   => ['pipe', 'r'],
            1   => ['pipe', 'w'],
            2   => ['pipe', 'w'],
        ], $pipes, $env);

        if (!is_resource($proc)) {
            throw new UnexpectedValueException('Call to proc_open failed');
        }

        fwrite($pipes[0], $code);
        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($proc);

        return [$stdout, $stderr];
    }
}