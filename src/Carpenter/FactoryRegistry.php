<?php

namespace Carpenter;

use ReflectionClass;

class FactoryRegistry
{
    private static $factories = [];

    public static function registerFactories($factoryClasses)
    {
        foreach ((array) $factoryClasses as $class) {
            self::addFactoryForClass($class);
        }
    }

    public static function clear()
    {
        self::$factories = [];
    }

    public static function getTemplateForFactory($name)
    {
        return self::$factories[$name];
    }

    public static function isFactoryDefined($name)
    {
        return isset(self::$factories[$name]);
    }

    private static function addFactoryForClass($class)
    {
        $name = static::getFactoryName($class);

        self::$factories[$name] = self::buildTemplate($class);
    }

    private static function getFactoryName($class)
    {
        $namespaces = explode('\\', $class);

        return preg_replace('/Factory$/', '', array_pop($namespaces));
    }

    private static function buildTemplate($class)
    {
        $reflection = new ReflectionClass($class);
        $parser = new FactoryParser($reflection);

        return $parser->getTemplate();
    }
}
