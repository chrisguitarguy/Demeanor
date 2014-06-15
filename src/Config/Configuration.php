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
 * Represents the overall configuration of the test runner. This is responsible
 * for loading configuration from files and making sure it's valid.
 *
 * @since   0.1
 */
interface Configuration
{
    /**
     * Manually set the configuration file name.
     *
     * @since   0.1
     * @param   string $filename
     * @return  void
     */
    public function setFile($filename);

    /**
     * Initialize the configuration, loading any files required, and validating
     * that everything is okay.
     *
     * @since   0.1
     * @throws  Demeanor\Exception\ConfigurationException
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
     * Check to see if the a test suite can run -- based on the configuation
     * options.
     *
     * @since   0.1
     * @param   string $suiteName
     * @return  boolean
     */
    public function suiteCanRun($suiteName);

    /**
     * Get the listeners to be added to the event emitter.
     *
     * @since   0.1
     * @return  Demeanor\Event\Subscriber
     */
    public function getEventSubscribers();

    /**
     * Get the filters defined by the configuration.
     *
     * @since   0.2
     * @return  Demeanor\Filter\Filter
     */
    public function getFilters();

    /**
     * Check to see if coverage is enabled.
     *
     * @since   0.3
     * @return  boolean
     */
    public function coverageEnabled();

    /**
     * Create the Finder object that will be used for coverage.
     *
     * @since   0.3
     * @return  Finder
     */
    public function coverageFinder();

    /**
     * Get the associative array of report objects + output paths for
     * code coverage
     *
     * @since   0.3
     * @return  array
     */
    public function coverageReports();
}
