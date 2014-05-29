# Data Providers

Data providers let you run the same test with multiple sets of arguments. Any
`TestCase` implementation can have a provider, but currently they are only
really first class citizens in the unit test world on Demeanor.

## Annotations

Data providers are set on unit tests using the `@Provider` annotation.

Data providers can be one of three types:

1. A static method on the test class -- `@Provider(method="someMethod")` or
   `@Provider("someMethod")`
2. A function -- `@Provider(function="a_provider_function")`
3. Inline
    - `@Provider(data=["one", "two"])`
    - `@Provider(data={aKey: ["data", "set"], anotherKey: "dataset"})`

### Static Method Data Provider

    <?php
    use Demeanor\TestContext;
    use Counterpart\Assert;

    class DataProviderMethodTest
    {
        public static function aProvider()
        {
            return [
                'one',
                'two',
            ];
        }

        /**
         * These two are the same:
         * @Provider("aProvider")
         * @Provider(method="aProvider")
         */
        public function testWithMethodProvider(TestContext $ctx, $arg)
        {
            Assert::assertType('string', $arg);
        }
    }

### Function Data Provider

    <?php
    use Demeanor\TestContext;
    use Counterpart\Assert;

    function acceptance_dataprovider_function()
    {
        return [
            'one',
            'two',
        ];
    }

    class DataProviderFunctionTest
    {
        /**
         * @Provider(function="acceptance_dataprovider_function")
         */
        public function testWithFunctionProvider(TestContext $ctx, $arg)
        {
            Assert::assertType('string', $arg);
        }
    }

### Inline Data Provider

    <?php
    use Demeanor\TestContext;
    use Counterpart\Assert;

    class DataProviderInlineTest
    {
        /**
         * @Provider(data=["one", "two"])
         */
        public function testWithDataProviderAsIndexedArray(TestContext $ctx, $arg)
        {
            Assert::assertType('string', $arg);
        }

        /**
         * @Provider(data={aSet: "one", anotherSet: "two"})
         */
        public function testWithDataProviderAsAssociativeArray(TestContext $ctx, $arg)
        {
            Assert::assertType('string', $arg);
        }
    }

## The Test Context

Notice the the [test context](03-the-test-context.md) is *always* the first argument
to test methods. In Demeanor the context object is important, and any data
provider arguments will come after it.
