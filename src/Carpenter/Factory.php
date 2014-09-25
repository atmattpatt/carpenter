<?php

namespace Carpenter;

class Factory
{
    public static function build()
    {
        $arguments = func_get_args();
        $modifiers = [];
        $overrides = [];
        $factory = array_shift($arguments);

        if (count($arguments) > 1) {
            $overrides = array_pop($arguments);
            $modifiers = $arguments;
        } elseif (count($arguments) > 0 && is_array($arguments[0])) {
            $overrides = $arguments;
        } elseif (count($arguments) > 0 && is_string($arguments[0])) {
            $modifiers = $arguments;
        }

        $template = FactoryRegistry::getTemplateForFactory($factory);
        $resolved = $template->resolve($modifiers, $overrides);

        return $template->apply(Configuration::$adapter, $resolved);
    }


    public static function create()
    {
        $built = call_user_func_array('static::build', func_get_args());
        Configuration::$adapter->persist($built);

        return $built;
    }

    public static function discoverFactories()
    {
        $finder = new FactoryFinder();
        FactoryRegistry::registerFactories($finder->discoverFactories());
    }
}
