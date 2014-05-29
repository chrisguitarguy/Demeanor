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

namespace Demeanor\Extension\Annotation;

use Demeanor\Unit\UnitTestCase;

function _dp_test_return()
{
    return [
        'one',
        ['two'],
    ];
}

class DataProviderTest extends AnnotationTestCase
{
    public function testNonStaticMethodsAreNotTreatedAsValidProviders()
    {
        $testcase = $this->testCaseMock();
        $annot = new DataProvider([], ['method' => 'notStatic']);
        $this->willNotHaveProvider($testcase);

        $annot->attachSetup($testcase);
    }

    public function testStaticMethodIsCalledAsTheDataProvider()
    {
        $testcase = $this->testCaseMock();
        $this->willHaveProvider($testcase);
        $annot = new DataProvider([], ['method' => 'isStatic']);

        $annot->attachSetup($testcase);
    }

    public function testFirstPositionalArgumentIsTreatedAsMethodDataProvider()
    {
        $testcase = $this->testCaseMock();
        $this->willHaveProvider($testcase);
        $annot = new DataProvider(['isStatic'], []);

        $annot->attachSetup($testcase);
    }

    public function testPositionalArgumentWillNotAddMethodIfItsInvalid()
    {
        $testcase = $this->testCaseMock();
        $annot = new DataProvider(['notStatic'], []);
        $this->willNotHaveProvider($testcase);

        $annot->attachSetup($testcase);
    }

    public function testInvalidFunctionIsNotTreatedAsAValidDataProvider()
    {
        $testcase = $this->testCaseMock();
        $this->willNotHaveProvider($testcase);
        $annot = new DataProvider([], ['function' => __NAMESPACE__.'\\does_not_exist_as_a_function']);

        $annot->attachSetup($testcase);
    }

    public function testValidFunctionIsCalledAsTheDataProvider()
    {
        $testcase = $this->testCaseMock();
        $this->willHaveProvider($testcase);
        $annot = new DataProvider([], ['function' => __NAMESPACE__.'\\_dp_test_return']);

        $annot->attachSetup($testcase);
    }

    public function testNonArrayDataIsIgnoredForDataProviders()
    {
        $testcase = $this->testCaseMock();
        $this->willNotHaveProvider($testcase);
        $annot = new DataProvider([], ['data' => 'not an array']);

        $annot->attachSetup($testcase);
    }

    public function testArrayDataIsTreatedAsAValidDataProvider()
    {
        $testcase = $this->testCaseMock();
        $this->willHaveProvider($testcase);
        $annot = new DataProvider([], ['data' => ['one', ['two']]]);

        $annot->attachSetup($testcase);
    }

    public function notStatic()
    {
        
    }

    public static function isStatic()
    {
        return [
            'one',
            'two',
        ];
    }

    private function willNotHaveProvider($testcase)
    {
        $testcase->shouldReceive('withProvider')
            ->never();
    }

    private function willHaveProvider($testcase)
    {
        $testcase->shouldReceive('withProvider')
            ->once();
    }
}
