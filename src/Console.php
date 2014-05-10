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

namespace Demeanor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Command\HelpCommand;

/**
 * @see     http://symfony.com/doc/current/components/console/single_command_tool.html
 */
class Console extends Application
{
    public function __construct()
    {
        parent::__construct('Demeanor', Demeanor::VERSION);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        $inputDef = parent::getDefinition();
        $inputDef->setArguments();
        return $inputDef;
    }

    /**
     * Overridden so we can print the name version on everything
     * {@inheritdoc}
     */
    public function run(InputInterface $in=null, OutputInterface $out=null)
    {
        if (null === $in) {
            $in = new ArgvInput();
        }

        if (null === $out) {
            $out = new ConsoleOutput();
        }

        $out->writeln($this->getLongVersion());
        $out->writeln('');

        return parent::run($in, $out);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $in)
    {
        return Command::NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return [
            new HelpCommand(),
            new Command(),
        ];
    }
}
