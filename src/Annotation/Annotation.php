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

/**
 * Defines a single annotation. Annotations are simple value objects and containers
 * for the arguments passed into them.
 *
 * @since   0.5
 */
interface Annotation
{
    const ARGUMENT_NOT_FOUND = null;

    /**
     * The the position argument at $index. Given this annotation:
     *
     *      @Annotation("here")
     *
     * `getPositional` would:
     *
     *      $position = $someAnnotation->positional(0); // "here"
     *
     * @since   0.5
     * @param   int $index The zero-based index of the positional argument
     * @return  mixed|null The positional argument value if its present, null otherwise
     */
    public function positional($index);

    /**
     * Get the argument named $name. Given this annotation:
     *
     *      @Annotation(one="two")
     *
     * `named` would:
     *
     *      $named = $someAnnotation->named('one'); // "two"
     *
     * @since   0.5
     * @param   string $name The named argument to fetch
     * @return  mixed|null The argument value if present, null otherwise
     */
    public function named($name);
}
