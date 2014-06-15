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

namespace Demeanor\Annotation;

use Demeanor\Unit\UnitTestCase;

/**
 * Set the data provider for a test case.
 *
 * @since   0.1
 */
class DataProvider extends Annotation
{
    /**
     * {@inheritdoc}
     */
    public function attachSetup(UnitTestCase $testcase)
    {
        $data = null;
        if ($this->hasValidMethod($testcase, true)) {
            $data = $this->callMethod($testcase);
        } elseif ($this->hasValidFunction($testcase)) {
            $data = $this->callFunc($testcase);
        } elseif (isset($this->args['data']) && is_array($this->args['data'])) {
            $data = $this->args['data'];
        }

        if ($data) {
            $testcase->withProvider($data);
        }
    }

    private function callMethod(UnitTestCase $testcase)
    {
        return call_user_func([$testcase->getReflectionClass()->getName(), $this->args['method']]);
    }

    private function callFunc(UnitTestCase $testcase)
    {
        return call_user_func($this->args['function']);
    }
}
