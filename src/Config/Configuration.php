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

use Demeanor\Exception\ConfigurationException;

/**
 * Represents the overall configuration of the test runner. This is responsible
 * for loading configuration from files and making sure it's valid.
 *
 * @since   0.1
 */
interface Configuration
{
    /**
     * Initialize the configuration, loading any files required, and validating
     * that everything is okay.
     *
     * @since   0.1
     * @throws  ConfigurtionException
     * @return  void
     */
    public function initialize();

    /**
     * Get all the test suites arrays defined in the configuration.
     *
     * @since   0.1
     * @return  array[]
     */
    public function getTestSuites();

    /**
     * Get the listeners to be added to the event emitter.
     *
     * @since   0.1
     * @return  Demeanor\Event\Subscriber
     */
    public function getEventSubscribers();
}
