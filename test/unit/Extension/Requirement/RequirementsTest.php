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

namespace Demeanor\Extension\Requirement;

use Counterpart\Assert;

class RequirementsTest
{
    public function testAddHasRemoveWorksAsExpected()
    {
        $reqs = new Requirements();
        $req = $this->requirement();

        Assert::assertFalse($reqs->has($req));
        Assert::assertFalse($reqs->remove($req));
        $reqs->add($req);
        Assert::assertTrue($reqs->has($req));
        Assert::assertTrue($reqs->remove($req));
        Assert::assertFalse($reqs->has($req));
        Assert::assertFalse($reqs->remove($req));
    }

    public function testClearRemovesAllRequirements()
    {
        $reqs = new Requirements();
        $req = $this->requirement();

        $reqs->add($req);
        Assert::assertTrue($reqs->has($req));
        $reqs->clear();

        Assert::assertFalse($reqs->has($req));
    }

    public function testCountReturnsExpectedValue()
    {
        $reqs = new Requirements();
        $req = $this->requirement();
        $reqs->add($req);

        Assert::assertCount(1, $reqs);
    }

    public function testRequirementsObjectIsIterable()
    {
        $reqs = new Requirements();
        $req = $this->requirement();
        $reqs->add($req);

        foreach ($reqs as $r) {
            Assert::assertInstanceOf('Demeanor\\Extension\\Requirement\\Requirement', $r);
        }
    }

    private function requirement()
    {
        return \Mockery::mock('Demeanor\\Extension\\Requirement\\Requirement');
    }
}
