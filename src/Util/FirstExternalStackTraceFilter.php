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
 * Used to filter stack traces down to the first frame that didn't originate
 * from a given library.
 *
 * This is useful for assertions which rely on locating where the call to assert*
 * occured.
 *
 * @since   0.2
 */
class FirstExternalStackTraceFilter extends AbstractStackTraceFilter
{
    /**
     * A reflection class instance pointing to a library class. This is used
     * to find the directory in which the library resides.
     *
     * @since   0.2
     * @var     string
     */
    private $refClass;

    /**
     * Constructor. Set up the library class name.
     *
     * @since   0.2
     * @param   string $className
     * @throws  InvalidArgumentException if a ReflectionClass couldn't be created
     * @return  void
     */
    public function __construct($className)
    {
        try {
            $this->refClass = new \ReflectionClass($className);
        } catch (\Exception $e) {
            throw new InvalidArgumentException(sprintf(
                'Could not create reflection class for %s',
                is_object($className) ? get_class($className) : (string)$className
            ), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doFilter(array $trace)
    {
        $prefix = $this->basedir();

        $rv = array();
        foreach ($trace as $frame) {
            if (!isset($frame['file']) || stripos($frame['file'], $prefix) === 0) {
                continue;
            }

            $rv[] = $frame;
            break;
        }

        return $rv;
    }

    private function basedir()
    {
        return dirname($this->refClass->getFileName());
    }
}
