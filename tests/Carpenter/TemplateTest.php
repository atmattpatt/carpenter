<?php

namespace Carpenter;

use Fixture\Carpenter\BasicUserFactory;
use Fixture\Carpenter\DynamicUserFactory;
use Fixture\Carpenter\ModifierUserFactory;
use Fixture\Carpenter\ComprehensiveUserFactory;
use ReflectionMethod;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $factory = new DynamicUserFactory();
        $template = new Template($factory);
        $template->addDeferred('password');
        $expected = sha1($factory->username . "password1");

        $template->resolve();

        $this->assertEquals($expected, $factory->password);
    }

    public function testResolveWithModifiers()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);
        $template->addModifier('deleted');

        $template->resolve(['deleted']);

        $this->assertEquals('deleted', $factory->status);
        $this->assertNull($factory->password);
    }

    public function testResolveWithOverrides()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);

        $template->resolve([], ['status' => 'suspended']);

        $this->assertEquals('suspended', $factory->status);
        $this->assertEquals('password1', $factory->password);
    }

    public function testConstructor()
    {
        $factory = new BasicUserFactory();

        $template = new Template($factory);

        $this->assertAttributeSame($factory, 'factory', $template);
    }

    public function testAddDeferred()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);

        $template->addDeferred('password');

        $this->assertAttributeContains('password', 'deferreds', $template);
    }

    public function testAddModifier()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);

        $template->addModifier('deleted');

        $this->assertAttributeContains('deleted', 'modifiers', $template);
    }

    public function testSetTargetClass()
    {
        $factory = new BasicUserFactory();
        $template = new Template($factory);

        $template->setTargetClass('\Fixture\Carpenter\User');

        $this->assertAttributeEquals('\Fixture\Carpenter\User', 'targetClass', $template);
    }
}
