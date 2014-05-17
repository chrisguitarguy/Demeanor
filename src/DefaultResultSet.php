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

class DefaultResultSet implements ResultSet
{
    private $errors;
    private $failed;
    private $skipped;
    private $success;
    private $all;

    public function __construct()
    {
        $this->errors = new \SplObjectStorage();
        $this->failed = new \SplObjectStorage();
        $this->skipped = new \SplObjectStorage();
        $this->success = new \SplObjectStorage();
        $this->all = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function add(TestCase $test, TestResult $result)
    {
        $this->all[$test] = $result;

        if ($result->skipped()) {
            $this->skipped[$test] = $result;
        } elseif ($result->errored()) {
            $this->errors[$test] = $result;
        } elseif ($result->failed()) {
            $this->failed[$test] = $result;
        } else {
            $this->success[$test] = $result;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function errorCount()
    {
        return count($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function failedCount()
    {
        return count($this->failed);
    }

    /**
     * {@inheritdoc}
     */
    public function skippedCount()
    {
        return count($this->skipped);
    }

    /**
     * {@inheritdoc}
     */
    public function successCount()
    {
        return count($this->success);
    }

    /**
     * {@inheritdoc}
     */
    public function successful()
    {
        return $this->errorCount() === 0 && $this->failedCount() === 0;
    }

    /**
     * Return the total count of tests.
     *
     * @since   0.1
     * @return  int
     */
    public function count()
    {
        return count($this->all);
    }
}
