<?php

namespace Carpenter;

use Fixture\Carpenter\BasicUserFactory;
use Fixture\Carpenter\DynamicUserFactory;
use Fixture\Carpenter\ModifierUserFactory;
use Fixture\Carpenter\ComprehensiveUserFactory;
use ReflectionMethod;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $factory = new DynamicUserFactory();
        $template = new Template($factory);
        $template->addDeferred('password');
        $adapter = new Adapter\ArrayAdapter();

        $resolved = $template->resolve();
        $built = $template->apply($adapter, $resolved);

        $this->assertEquals([
            'username' => 'Bob',
            'password' => sha1('Bobpassword1'),
        ], $built);
    }

    public function testResolve()
    {
        $factory = new DynamicUserFactory();
        $template = new Template($factory);
        $template->addDeferred('password');
        $expected = sha1($factory->username . "password1");

        $resolved = $template->resolve();

        $this->assertInstanceOf('\Fixture\Carpenter\DynamicUserFactory', $resolved);
        $this->assertNotSame($factory, $resolved);
        $this->assertEquals($expected, $resolved->password);
    }

    public function testResolveWithModifiers()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);
        $template->addModifier('deleted');

        $resolved = $template->resolve(['deleted']);

        $this->assertEquals('deleted', $resolved->status);
        $this->assertNull($resolved->password);
    }

    public function testResolveWithOverrides()
    {
        $factory = new ModifierUserFactory();
        $template = new Template($factory);

        $resolved = $template->resolve([], ['status' => 'suspended']);

        $this->assertEquals('suspended', $resolved->status);
        $this->assertEquals('password1', $resolved->password);
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
