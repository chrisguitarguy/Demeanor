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
 * A StackTraceFilter implementation that limits traces to frames that include
 * a `file` key that points to a valid file.
 *
 * @since   0.2
 */
class FileStackTraceFilter extends AbstractStackTraceFilter
{
    /**
     * {@inheritdoc}
     */
    protected function doFilter(array $trace)
    {
        $rv = array();
        foreach ($trace as $frame) {
            if (!isset($frame['file']) || !is_file($frame['file'])) {
                continue;
            }

            $rv[] = $frame;
        }

        return $rv;
    }
}