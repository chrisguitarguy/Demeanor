# Test Result Statuses

Ever `Demeanor\TestCase` implementation produces an implementation of
`Demeanor\TestResult` when it's run. `TestResult` can have one of many statuses
that are explained here.

### Successful

The test worked! To demeanor this just means that no exceptions were thrown
during the core of the test run.

### Skipped

The test was explicitly marked as skipped via `Demeanor\TestContext::skip` or
some sort of requirement for the tests execution was not met.

A skipped test does not cause the Demanor CLI to exit with a failure code.

Users can skip tests if some precondition is not met. Need a certain environment
variable for a test to work? Didn't get it? Skip the test.

### Errored

An unexpected exception or warning occurred during the execution of the test.

### Failed

The test was explicitly marked as failed via `Demeanor\TestContext::fail` or an
assertion failed.

### Filtered

This status is only used internally by demeanor to "skip" tests with out really
skipping them. Filtered means some filter condition (like name or otherwise) was
not met and the test was simply not executed.

## How Test Result Statuses Influence CLI Exit Codes

If one or more tests fail or error, Demeanor will exit unsuccessfully. See
[Exit Codes](07-exit-codes.md) for more information.
