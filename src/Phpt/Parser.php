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

use Demeanor\Exception\InvalidArgumentException;
use Demeanor\Exception\UnexpectedValueException;

/**
 * Parses `.phpt` files in $section => $code pairs.
 *
 * A section starts with `--SECTION_NAME--` and continues until the next section
 * name.
 *
 * As an example:
 *
 *      --TEST--
 *      This is in the TEST section
 *      --FILE--
 *      This is in the file section.
 *      Still here
 *
 * Would be parsed into:
 *
 *      [
 *          'TEST'  => 'This is in the TEST section'
 *          'FILE'  => 'This is in the file section.\nstill here'
 *      ]
 *
 * The parser doesn't care about whether the sections are valid or usable.
 *
 * @since   0.1
 */
class Parser
{
    /**
     * Parse the file.
     *
     * @since   0.1
     * @param   array|Traversable $file
     * @throws  InvalidArgumentException if $file isn't an array or Traversable
     * @throws  UnexpectedValueException if $file can't be parsed.
     * @return  array Described in the class docblock above
     */
    public function parse($file)
    {
        if (!is_array($file) && !$file instanceof \Traversable) {
            throw new InvalidArgumentException(sprintf('%s expected $file to be an array or Traversable', __METHOD__));
        }

        $sections = array();
        $section = null;
        foreach ($file as $line) {
            if (preg_match('/^--(?P<section>[A-Z_]+)--$/u', trim($line), $matches)) {
                $section = $matches['section'];
                if (isset($sections[$section])) {
                    throw new UnexpectedValueException(sprintf('Section %s found multiple times', $section));
                }
                $sections[$section] = '';
                continue;
            }

            if (!$section) {
                throw new UnexpectedValueException('Could not add line to section because, $section not set');
            }

            $sections[$section] .= $line;
        }

        return $sections;
    }
}
