<?php

namespace Carpenter\TestListener;

use Carpenter\Configuration;

class TransactionalFixtureTestListener implements \PHPUnit_Framework_TestListener
{
    public function startTest(\PHPUnit_Framework_Test $test)
    {
        Configuration::$adapter->beginTransaction();
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        Configuration::$adapter->rollback();
    }

    /** @codeCoverageIgnore */
    public function addError(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /** @codeCoverageIgnore */
    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /** @codeCoverageIgnore */
    public function addIncompleteTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /** @codeCoverageIgnore */
    public function addRiskyTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /** @codeCoverageIgnore */
    public function addSkippedTest(\PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /** @codeCoverageIgnore */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    /** @codeCoverageIgnore */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }
}
