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

/**
 * Metadata implementations act as a container for extra information. Usually
 * this interface is extended, but it may be used on its own.
 *
 * Some terminology:
 *  - A "group" is a named collection of related metadata. Line tags.
 *  - A "name" is the key of a specific bit of meta data. Where tags are a group
 *    a specific tag has a name.
 *  - A "value" is the value assigned to the metadata name. This won't be used
 *    in all cases and is always optional.
 *
 * @since   0.2
 */
interface Metadata
{
    /**
     * Check to see if a piece of metadata exists.
     *
     * @since   0.2
     * @param   string $group
     * @param   string $name
     * @return  boolean
     */
    public function hasMeta($group, $name);

    /**
     * Get the metadata value for a specific group and name
     *
     * @since   0.2
     * @param   string $group
     * @param   string $name
     * @return  mixed
     */
    public function getMeta($group, $name);

    /**
     * Add a new piece of metadata only if it doesn't exist.
     *
     * @since   0.2
     * @param   string $group
     * @param   string $name
     * @param   mixed $value optional, defaults to true
     * @return  void
     */
    public function addMeta($group, $name, $value=true);

    /**
     * Set a piece of metadata to a value, regardless of whether or not it exists
     *
     * @since   0.2
     * @param   string $group
     * @param   string $name
     * @param   mixed $value optional, defaults to true
     * @return  void
     */
    public function setMeta($group, $name, $value=true);

    /**
     * Remove a piece of metadata from the object.
     *
     * @since   0.2
     * @param   string $group
     * @param   string $name
     * @return  boolean True if the value was removed
     */
    public function removeMeta($group, $name);
}
