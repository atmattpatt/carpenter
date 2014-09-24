<?php

namespace Carpenter;

class FactoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterFactories()
    {
        $factories = array('Fixture\Carpenter\BasicUserFactory');

        FactoryRegistry::registerFactories($factories);

        $this->assertTrue(FactoryRegistry::isFactoryDefined('BasicUserFactory'));
    }
}
