<?php

namespace Carpenter;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected static $originalAdapter;

    public function setUp()
    {
        Configuration::$adapter = new Adapter\ArrayAdapter();
        Factory::discoverFactories();
    }

    public function tearDown()
    {
        FactoryRegistry::clear();
    }

    public function testBuildBasicFactory()
    {
        $user = Factory::build('BasicUser');

        $this->assertEquals([
            'username' => 'Bob',
            'password' => 'password1',
        ], $user);
    }

    public function testBuildDynamicFactory()
    {
        $user = Factory::build('DynamicUser');

        $this->assertEquals([
            'username' => 'Bob',
            'password' => sha1('Bobpassword1'),
        ], $user);
    }

    public function testBuildTraitFactory()
    {
        $user = Factory::build('ModifierUser', 'deleted');

        $this->assertEquals([
            'username' => 'Bob',
            'password' => null,
            'status' => 'deleted',
        ], $user);
    }

    public function testCreateBasicFactory()
    {
        Configuration::$adapter = $this->getMockBuilder('\Carpenter\Adapter\ArrayAdapter')
            ->setMethods(['persist'])
            ->getMock();

        $expected = [
            'username' => 'Bob',
            'password' => 'password1',
        ];

        Configuration::$adapter->expects($this->once())
            ->method('persist')
            ->will($this->returnValue($expected));

        $user = Factory::create('BasicUser');

        $this->assertEquals($expected, $user);
    }

    public function testDiscoverFactories()
    {
        Configuration::$factoryPaths = [__DIR__ . '/../Fixture'];
        Factory::discoverFactories();

        $this->assertTrue(FactoryRegistry::isFactoryDefined('BasicUser'));
        $this->assertTrue(FactoryRegistry::isFactoryDefined('DynamicUser'));
        $this->assertTrue(FactoryRegistry::isFactoryDefined('ModifierUser'));
    }
}
