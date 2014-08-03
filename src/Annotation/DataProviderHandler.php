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

use Demeanor\TestCase;

class DataProviderHandler extends AbstractHandler
{
    use MethodValidatorTrait;
    use FunctionValidatorTrait;
    use NameNormalizationTrait;

    /**
     * {@inheritdoc}
     */
    public function onSetup(Annotation $annotation, TestCase $testcase)
    {
        $foundProvider = false;
        $providerData = null;

        $method = $first = $annotation->positional(0);
        if (!$method) {
            $method = $annotation->named('method');
        }

        if (is_string($method) && $this->isValidStaticMethod($method, $testcase)) {
            return $testcase->withProvider($this->callMethod($method, $testcase));
        }

        $func = $first;
        if (!$func) {
            $func = $annotation->named('function');
        }

        if (is_string($func) && $this->isValidFunction($this->normalizeName($func))) {
            return $testcase->withProvider($this->callFunc($this->normalizeName($func)));
        }

        $data = $first;
        if (!$data) {
            $data = $annotation->named('data');
        }

        if ($data && is_array($data)) {
            return $testcase->withProvider($data);
        }
    }

    private function callMethod($method, TestCase $testcase)
    {
        return call_user_func([$testcase->getReflectionClass()->getName(), $method]);
    }

    private function callFunc($func)
    {
        return call_user_func($func);
    }
}
