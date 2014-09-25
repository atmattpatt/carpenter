<?php

namespace Carpenter;

class FactoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterFactories()
    {
        $factories = ['Fixture\Carpenter\BasicUserFactory'];

        FactoryRegistry::registerFactories($factories);

        $this->assertTrue(FactoryRegistry::isFactoryDefined('BasicUser'));
    }

    public function testGetTemplateForFactory()
    {
        FactoryRegistry::registerFactories(['Fixture\Carpenter\BasicUserFactory']);

        $template = FactoryRegistry::getTemplateForFactory('BasicUser');

        $this->assertInstanceOf('\Carpenter\Template', $template);
        $this->assertAttributeInstanceOf('\Fixture\Carpenter\BasicUserFactory', 'factory', $template);
    }
}
