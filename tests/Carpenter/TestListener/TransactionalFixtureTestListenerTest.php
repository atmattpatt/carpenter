<?php

namespace Carpenter\TestListener;

use Carpenter\Configuration;

class TransactionalFixtureTestListenerTest extends \PHPUnit_Framework_TestCase
{
    protected static $originalAdapter;

    public function setUp()
    {
        static::$originalAdapter = Configuration::$adapter;
    }

    public function tearDown()
    {
        Configuration::$adapter = static::$originalAdapter;
    }

    public function testStartTestBeingsTransaction()
    {
        $listener = new TransactionalFixtureTestListener();

        $mockAdapter = $this->getMockAdapter();
        $mockAdapter->expects($this->once())
            ->method('beginTransaction');

        Configuration::$adapter = $mockAdapter;
        $listener->startTest($this->getMockTest());
    }

    public function testEndTestRollsBackTransaction()
    {
        $listener = new TransactionalFixtureTestListener();

        $mockAdapter = $this->getMockAdapter();
        $mockAdapter->expects($this->once())
            ->method('rollback');

        Configuration::$adapter = $mockAdapter;
        $listener->endTest($this->getMockTest(), null);
    }

    private function getMockAdapter()
    {
        return $this->getMockForAbstractClass('Carpenter\\Adapter\\AdapterInterface');
    }

    private function getMockTest()
    {
        return $this->getMock('PHPUnit_Framework_Test');
    }
}
