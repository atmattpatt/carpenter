<?php

namespace Carpenter;

class FactoryFinderTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructor()
	{
		$finder = new FactoryFinder();

		$this->assertAttributeContains(getcwd() . '/tests', 'paths', $finder);
	}

	public function testAddPath()
	{
		$finder = new FactoryFinder();
		$path = '/some/path/to/factories';

		$finder->addPath($path);

		$this->assertAttributeContains($path, 'paths', $finder);
	}

	public function testDiscoverFactories()
	{
		$finder = new FactoryFinder();
		$finder->addPath(realpath(__DIR__ . '/../Fixture'));

		$factories = $finder->discoverFactories();

		$this->assertContains('Fixture\Carpenter\BasicUserFactory', $factories);
	}
}
