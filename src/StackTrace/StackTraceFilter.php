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

namespace Demeanor\StackTrace;

/**
 * Allows filtering of a stack trace (from an exception debug_backtrace) into
 * something useful that can be presented to a user.
 *
 * @since   0.2
 */
interface StackTraceFilter
{
    /**
     * Filter a stack trace to an array of "frames" that's valid for the use
     * case.
     *
     * @since   0.2
     * @param   Exception|array $trace
     * @throws  InvalidArgumentException if $trace is neither an array or exception
     * @return  array
     */
    public function filterTrace($trace);


    /**
     * Turn an exception or stack trace array into a string. This will only print
     * frames that have at least a filename
     *
     * @since   0.2
     * @param   Exception|array $trace
     * @throws  InvalidArgumentException if $trace is neither an array or exception
     * @return  string
     */
    public function traceToString($trace, $eol=PHP_EOL);
}
