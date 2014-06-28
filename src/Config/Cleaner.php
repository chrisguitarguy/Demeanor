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

namespace Demeanor\Config;

/**
 * `Cleaner` objects transform configuration arrays (from whatever source) into
 * something safe to use.
 *
 * @since   0.3
 */
interface Cleaner
{
    /**
     * Clean a configuration array.
     *
     * Demeanor assumes that any incoming configuration, from whatever source,
     * is junk and needs to be sanitized and verified. This will transform a
     * potentially junk configuration into something usable or throw an exception
     * if that can't be done.
     *
     * @since   0.3
     * @param   array $config
     * @throws  Demeanor\Exception\ConfigurationException
     * @return  array
     */
    public function cleanConfig(array $config);
}
