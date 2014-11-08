<?php

namespace Carpenter\Adapter;

use Carpenter\Configuration;
use Carpenter\Factory;
use Carpenter\FactoryRegistry;

class DoctrineAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected static $originalAdapter;

    public function setUp()
    {
        static::$originalAdapter = Configuration::$adapter;
        Factory::discoverFactories();
    }

    public function tearDown()
    {
        FactoryRegistry::clear();
        Configuration::$adapter = static::$originalAdapter;
    }

    public function testBuildSingleEntity()
    {
        Configuration::$adapter = new DoctrineAdapter($this->getMockEntityManager());
        $user = Factory::build('DoctrineUser', 'deleted');

        $this->assertInstanceOf('Fixture\\Carpenter\\DoctrineUser', $user);
        $this->assertEquals('bob', $user->getUsername());
        $this->assertNull($user->getPassword());
        $this->assertEquals('deleted', $user->getStatus());
    }

    public function testPersistWithFlush()
    {
        $entityManager = $this->getMockEntityManager();
        Configuration::$adapter = new DoctrineAdapter($entityManager);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf('Fixture\\Carpenter\\DoctrineUser'));
        $entityManager->expects($this->once())
            ->method('flush');

        Factory::create('DoctrineUser');
    }

    public function testPersistWithoutFlush()
    {
        $entityManager = $this->getMockEntityManager();
        Configuration::$adapter = new DoctrineAdapter($entityManager, false);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf('Fixture\\Carpenter\\DoctrineUser'));
        $entityManager->expects($this->never())
            ->method('flush');

        Factory::create('DoctrineUser');
    }

    private function getMockEntityManager()
    {
        return $this->getMockForAbstractClass('Doctrine\\ORM\\EntityManagerInterface');
    }
}
