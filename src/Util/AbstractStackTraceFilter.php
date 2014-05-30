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

namespace Demeanor\Util;

use Demeanor\Exception\InvalidArgumentException;

/**
 * ABC for stack trace filters. Handles a good amount of the grunt work.
 *
 * @since   0.2
 */
abstract class AbstractStackTraceFilter implements StackTraceFilter
{
    /**
     * {@inheritdoc}
     */
    public function filterTrace($trace)
    {
        return $this->doFilter($this->extractTrace($trace));
    }

    /**
     * {@inheritdoc}
     */
    public function traceToString($trace, $eol=PHP_EOL)
    {
        $rv = '';
        foreach ($this->filterTrace($trace) as $frame) {
            if (!isset($frame['file'])) {
                continue;
            }

            $rv .= sprintf(
                '%s:%s%s',
                $frame['file'],
                isset($frame['line']) ? $frame['line'] : 'UNKNOWN',
                $eol
            );
        }

        return trim($rv);
    }

    /**
     * Extract the stack trace from $trace -- which may be an Exception or
     * just an array.
     *
     * @since   0.2
     * @param   $trace
     * @throws  InvalidArgumentException if $trace is no an exception or array
     * @return  array[]
     */
    protected function extractTrace($trace)
    {
        $ret = array();
        if ($trace instanceof \Exception) {
            $ret = $trace->getTrace();
            if (!$this->exceptionInTrace($trace, $ret)) {
                array_unshift($ret, [
                    'file'  => $trace->getFile(),
                    'line'  => $trace->getLine(),
                ]);
            }
        } elseif (is_array($trace)) {
            $ret = $trace;
        } else {
            throw new InvalidArgumentException('$trace must be an array or Exception');
        }

        return $ret;
    }

    /**
     * Check to see if an exception's line an file is already in the stack trace.
     *
     * @since   0.2
     * @param   Exception $e
     * @param   array $trace
     * @return  boolean
     */
    protected function exceptionInTrace(\Exception $e, array $trace)
    {
        $fn = $e->getFile();
        $ln = $e->getLine();

        foreach ($trace as $frame) {
            if (
                isset($frame['file']) && $fn == $frame['file'] &&
                isset($frame['line']) && $ln == $frame['line']
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Actually do the filtering according to the implementation.
     *
     * @since   0.2
     * @param   array[] $trace
     * @return  array[]
     */
    abstract protected function doFilter(array $trace);
}
