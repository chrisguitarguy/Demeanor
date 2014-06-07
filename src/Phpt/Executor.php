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

/**
 * Knows how to "execute" some PHP code in a separate process.
 *
 * PS: `Executor` is a badass interface name
 *
 * @since   0.1
 */
interface Executor
{
    /**
     * Run some code, returning the stdout and stderr as results.
     *
     * @since   0.1
     * @param   string $code
     * @param   array $env
     * @param   array $ini A set of INI values that will be set for the test
     * @throws  ProcessException when the $code can't be run for some reason.
     * @return  array [$stdout, $stderr]
     */
    public function execute($code, array $env, array $ini=[]);
}
