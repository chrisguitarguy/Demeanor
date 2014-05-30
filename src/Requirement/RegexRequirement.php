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

class RegexRequirement implements Requirement
{
    private $regexPattern;
    private $toCheck;
    private $what;

    public function __construct($regexPattern, $toCheck, $what)
    {
        $this->regexPattern = $regexPattern;
        $this->toCheck = $toCheck;
        $this->what = $what;
    }

    /**
     * {@inheritdoc}
     */
    public function met()
    {
        return (bool)preg_match($this->regexPattern, $this->toCheck);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s matching %s is required', $this->what, $this->regexPattern);
    }
}
