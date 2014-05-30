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

/**
 * A collection of individual requirements.
 *
 * @since   0.1
 */
class Requirements implements \IteratorAggregate, \Countable
{
    /**
     * The requirement storage object. We use SplObjectStorage here because
     * we don't really care about ordering.
     *
     * @since   0.1
     * @var     SplObjectStorage
     */
    private $storage;

    /**
     * Constructor set up the object storage.
     *
     * @since   0.1
     * @return  void
     */
    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    /**
     * Add a new requirement to the collection.
     *
     * @since   0.1
     * @param   Requirement $req
     * @return  void
     */
    public function add(Requirement $req)
    {
        $this->storage->attach($req);
    }

    /**
     * Check to see if the requirement exists in the collection.
     *
     * @since   0.1
     * @param   Requirement $req
     * @return  boolean
     */
    public function has(Requirement $req)
    {
        return $this->storage->contains($req);
    }

    /**
     * Remove a requirement if it exists.
     *
     * @since   0.1
     * @param   Requirement $req
     * @return  boolean True if the requirement was removed
     */
    public function remove(Requirement $req)
    {
        if ($this->has($req)) {
            $this->storage->detach($req);
            return true;
        }

        return false;
    }

    /**
     * Remove all the requirements
     *
     * @since   0.1
     * @return  void
     */
    public function clear()
    {
        $this->storage = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \IteratorIterator($this->storage);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->storage);
    }
}
