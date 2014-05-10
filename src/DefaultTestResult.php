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

class DefaultTestResult implements TestResult
{
    private $status = self::STATUS_SUCCESS;
    private $messages = array();

    /**
     * {@inheritdoc}
     */
    public function successful()
    {
        return self::STATUS_SUCCESS === $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function fail()
    {
        $this->status = self::STATUS_FAILED;
    }

    /**
     * {@inheritdoc}
     */
    public function failed()
    {
        return self::STATUS_FAILED === $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function skip()
    {
        $this->status = self::STATUS_SKIPPED;
    }

    /**
     * {@inheritdoc}
     */
    public function skipped()
    {
        return self::STATUS_SKIPPED === $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function error()
    {
        $this->status = self::STATUS_ERROR;
    }

    /**
     * {@inheritdoc}
     */
    public function errored()
    {
        return self::STATUS_ERROR === $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage($messageType, $message)
    {
        if (!isset($this->messages[$messageType])) {
            $this->messages[$messageType] = array();
        }

        $this->messages[$messageType][] = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }
}
