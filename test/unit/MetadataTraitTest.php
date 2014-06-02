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

use Counterpart\Assert;

// the interface is implemented here so we can be sure that the trait contains
// the entirety of the implementation (PHP will complain otherwise)
class _MetadataTraitStub implements Metadata
{
    use MetadataTrait;
}

class MetadataTraitTest
{
    const K = 'a_meta_key';
    private $meta;

    public function __construct()
    {
        $this->meta = new _MetadataTraitStub();
    }

    public function testHasAddGetRemoveRespectTheValuesStored()
    {
        Assert::assertFalse($this->meta->hasMeta(self::K));

        Assert::assertTrue($this->meta->addMeta(self::K));
        Assert::assertFalse($this->meta->addMeta(self::K, 'not a bool'), 'metadata should only be added once');

        Assert::assertTrue($this->meta->hasMeta(self::K));
        Assert::assertTrue($this->meta->getMeta(self::K));

        Assert::assertTrue($this->meta->removeMeta(self::K));
        Assert::assertFalse($this->meta->removeMeta(self::K), 'can only remove metadata once');
        Assert::assertFalse($this->meta->hasMeta(self::K), 'hasMeta should be false after key is removed');
    }

    public function testGetMetadataReturnsNullWhenKeyDoesNotExist()
    {
        Assert::assertFalse($this->meta->hasMeta(self::K));
        Assert::assertNull($this->meta->getMeta(self::K));
    }
}
