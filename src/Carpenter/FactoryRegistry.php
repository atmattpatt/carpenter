<?php

namespace Carpenter;

use ReflectionClass;

/**
 * Registry of all factories available for use
 */
class FactoryRegistry
{
    /**
     * An key/value map of factory names and template classes
     * @var array
     */
    private static $factories = [];

    /**
     * Registers factories for use
     *
     * @param array An array of factory class names
     */
    public static function registerFactories($factoryClasses)
    {
        foreach ((array) $factoryClasses as $class) {
            self::addFactoryForClass($class);
        }
    }

    /**
     * Clears the registry of all known factories
     */
    public static function clear()
    {
        self::$factories = [];
    }

    /**
     * Get the Template object for a given factory name
     *
     * @param string $name The name of the factory
     * @return Carpenter\Template The template for building the fixture
     * @throws Carpenter\FactoryNotFoundException if the factory is not registered
     */
    public static function getTemplateForFactory($name)
    {
        if (!static::isFactoryDefined($name)) {
            throw new FactoryNotFoundException(sprintf('"%s" is not a registered factory', $name));
        }

        return self::$factories[$name];
    }

    /**
     * Determines if a factory is registered
     *
     * @param string $name The name of the factory in question
     * @return bool
     */
    public static function isFactoryDefined($name)
    {
        return isset(self::$factories[$name]);
    }

    /**
     * Adds a factory template to the list of known factories
     *
     * @param string $class The factory class
     */
    private static function addFactoryForClass($class)
    {
        $name = static::getFactoryName($class);

        self::$factories[$name] = self::buildTemplate($class);
    }

    /**
     * Calculate the name of the factory
     *
     * The name of the factory is the unqualified class name of the factory, stripped of the "Factory" suffix
     *
     * @example
     * \Foo\Bar\UserFactory becomes User
     *
     * @param string $class The factory class
     * @return string
     */
    private static function getFactoryName($class)
    {
        $namespaces = explode('\\', $class);

        return preg_replace('/Factory$/', '', array_pop($namespaces));
    }

    /**
     * Parses the factory class and gets a template for building fixtures
     *
     * @param string $class The factory class
     * @return Carpenter\Template
     */
    private static function buildTemplate($class)
    {
        $reflection = new ReflectionClass($class);
        $parser = new FactoryParser($reflection);

        return $parser->getTemplate();
    }
}
