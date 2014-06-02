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

/**
 * The complete metadata implementation wrapped up in a trait.
 *
 * See the Metadata interface for documentation on the methods here.
 *
 * @since   0.2
 */
trait MetadataTrait
{
    private $metadata = array();

    /**
     * {@inheritdoc}
     */
    public function hasMeta($name)
    {
        return array_key_exists($name, $this->metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($name)
    {
        return $this->hasMeta($name) ? $this->metadata[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function addMeta($name, $value=true)
    {
        if ($this->hasMeta($name)) {
            return false;
        }

        $this->setmeta($name, $value);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta($name, $value=true)
    {
        $this->metadata[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMeta($name)
    {
        if ($this->hasMeta($name)) {
            unset($this->metadata[$name]);
            return true;
        }

        return false;
    }
}
