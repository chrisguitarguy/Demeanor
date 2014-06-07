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

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Demeanor\Exception\ProcessException;

/**
 * Uses Symfony's process component to run PHP code. We can't use PhpProcess
 * here due to the INI settings.
 *
 * @since   0.1
 */
class SymfonyExecutor implements Executor
{
    /**
     * The path to the PHP binary
     *
     * @since   0.2
     * @var     string
     */
    private $phpBinary;

    /**
     * Constructor. Optionally pass in the PHP binary location or one will be
     * looked up with a symfony excutable finder.
     *
     * @since   0.2
     * @param   string $phpBinary
     * @return  void
     */
    public function __construct($phpBinary=null)
    {
        $this->phpBinary = $phpBinary ?: $this->locateBinary();
    }

    /**
     * {@inheritdoc}
     */
    public function execute($code, array $env, array $ini=[])
    {
        $command = $this->buildCommand($ini);
        try {
            $proc = new Process($command, null, $env, $code);
            $proc->run();
            return [$proc->getOutput(), $proc->getErrorOutput()];
        } catch (\Exception $e) {
            throw new ProcessException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function buildCommand(array $ini)
    {
        $cmd = $this->phpBinary;
        foreach ($ini as $key => $val) {
            $arg = "{$key}={$val}";
            $cmd .= sprintf(
                ' --define %s',
                escapeshellarg($arg)
            );
        }

        return $cmd;
    }

    private function locateBinary()
    {
        $finder = new PhpExecutableFinder();
        $binary = $finder->find();
        if (!$binary) {
            throw new ProcessException('Could not locate PHP Binary');
        }

        return $binary;
    }
}
