<?php

namespace Carpenter;

class FactoryRegistry
{
    private static $factories = [];

    public static function registerFactories($factoryClasses)
    {
        foreach ((array) $factoryClasses as $class) {
            self::addFactoryForClass($class);
        }
    }

    public static function isFactoryDefined($factory)
    {
        return isset(self::$factories[$factory]);
    }

    private static function addFactoryForClass($class)
    {
        $name = static::getFactoryName($class);

        self::$factories[$name] = true;
    }

    private static function getFactoryName($class)
    {
        $namespaces = explode('\\', $class);

        return array_pop($namespaces);
    }
}
