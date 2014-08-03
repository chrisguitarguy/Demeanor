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

namespace Demeanor\Requirement;

/**
 * A static service locator to keep track of a global instance (the horror!) of
 * `RequirementsStorage`.
 *
 * @since   0.5
 */
class StorageLocator
{
    /**
     * @var     RequirementStorage
     */
    private static $storage = null;

    /**
     * Get the RequirementsStorage instance. If one is not set, a new instance
     * will be created.
     *
     * @since   0.5
     * @return  RequirementsStorage
     */
    public static function get()
    {
        if (null === self::$storage) {
            self::$storage = new RequirementsStorage();
        }

        return self::$storage;
    }

    /**
     * Set the instance of requirements storage.
     *
     * @since   0.5
     * @param   RequirementsStorage $store
     * @return  void
     */
    public static function set(RequirementsStorage $store)
    {
        self::$storage = $store;
    }

    /**
     * Clear the requirements storage instance (set it back to null) so a new
     * one is created the next time `set` is called.
     *
     * @since   0.5
     * @return  void
     */
    public static function remove()
    {
        self::$storage = null;
    }
}
