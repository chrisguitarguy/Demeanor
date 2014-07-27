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

namespace Demeanor\Filter;

class DirectoryFilterTest
{
    use \Counterpart\Assert;

    /**
     * @Expect("Demeanor\Exception\InvalidArgumentException")
     */
    public function testInstantiationThrowsExceptionWhenThePathDoesNotExist()
    {
        new DirectoryFilter('/etc/does/not/exist/at/all');
    }

    /**
     * @Expect("Demeanor\Exception\InvalidArgumentException")
     */
    public function testInstantiationThrowsExceptionWhenThePathIsNotADirectory()
    {
        new DirectoryFilter(__FILE__);
    }

    public function testTestCaseIsRunnableWhenATestCaseIsInTheSuppliedDirectory()
    {
        $tc = $this->testCaseInFile(__FILE__);
        $filter = new DirectoryFilter(__DIR__);

        $this->assertTrue($filter->canRun($tc));
    }

    public function testTestCaseIsNotRunnableWhenNotInSuppliedDirectory()
    {
        $tc = $this->testCaseInFile(realpath(__DIR__.'/../DefaultTestResultTest.php'));
        $filter = new DirectoryFilter(__DIR__);

        $this->assertFalse($filter->canRun($tc));
    }

    private function testCaseInFile($file)
    {
        $tc = \Mockery::mock('Demeanor\\TestCase');
        $tc->shouldReceive('filename')
            ->andReturn($file);

        return $tc;
    }
}
