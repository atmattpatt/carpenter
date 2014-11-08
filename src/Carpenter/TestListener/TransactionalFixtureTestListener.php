<?php

namespace Carpenter\TestListener;

use Carpenter\Configuration;

/**
 * PHPUnit test listener to warp all fixtures in a database transaction
 */
class TransactionalFixtureTestListener implements \PHPUnit_Framework_TestListener
{
    /**
     * Begins a transaction prior to starting a PHPUnit test
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(\PHPUnit_Framework_Test $test)
    {
        Configuration::$adapter->beginTransaction();
    }

    /**
     * Rolls back the transaction after a PHPUnit test completes
     *
     * @param PHPUnit_Framework_Test $test
     * @param int $time
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        Configuration::$adapter->rollback();
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }
}
