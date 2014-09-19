<?php

namespace Carpenter;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildBasicFactory()
    {
        $user = Factory::build('BasicUser');

        $this->assertInstanceOf('\Fixture\Cartpenter\User', $user);
        $this->assertAttributeEquals('Bob', 'username', $user);
        $this->assertAttributeEquals('password1', 'password', $user);
    }

    public function testBuildDynamicFactory()
    {
        $user = Factory::build('DynamicUser');

        $this->assertInstanceOf('\Fixture\Cartpenter\User', $user);
        $this->assertAttributeEquals('Bob', 'username', $user);
        $this->assertAttributeEquals('password1', 'password', $user);
    }

    public function testBuildTraitFactory()
    {
        $user = Factory::build('TraitUser', 'deleted');

        $this->assertInstanceOf('\Fixture\Cartpenter\User', $user);
        $this->assertAttributeEquals('Bob', 'username', $user);
        $this->assertAttributeEquals(null, 'password', $user);
        $this->assertAttributeEquals('deleted', 'status', $user);
    }
}
