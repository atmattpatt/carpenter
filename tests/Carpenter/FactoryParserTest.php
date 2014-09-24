<?php

namespace Carpenter;

use ReflectionClass;

class FactoryParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTemplate()
    {
        $reflection = new ReflectionClass('\Fixture\Carpenter\ComprehensiveUserFactory');
        $parser = new FactoryParser($reflection);

        $template = $parser->getTemplate();

        $this->assertInstanceOf('\Carpenter\Template', $template);
        $this->assertAttributeInstanceOf('\Fixture\Carpenter\ComprehensiveUserFactory', 'factory', $template);
        $this->assertAttributeContains('salt', 'deferreds', $template);
        $this->assertAttributeContains('deleted', 'modifiers', $template);
        $this->assertAttributeEquals('\Fixture\Carpenter\User', 'targetClass', $template);
    }
}
