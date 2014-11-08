<?php

namespace Carpenter;

/**
 * Primary interface for building fixtures
 */
class Factory
{
    /**
     * Build a new fixture without persisting it
     *
     * @param string $factory The name of the factory to build
     * @param string $modifiers.. One or more modifiers to apply to the fixture
     * @param array $overrides A key/value set of properties to override
     * @return mixed The fixture specified by the factory
     * @throws Carpenter\FactoryNotFoundException
     */
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

    /**
     * Build a new fixture and persist it in a data store
     *
     * @param string $factory The name of the factory to build
     * @param string $modifiers.. One or more modifiers to apply to the fixture
     * @param array $overrides A key/value set of properties to override
     * @return mixed The fixture specified by the factory
     * @throws Carpenter\FactoryNotFoundException
     */
    public static function create()
    {
        $built = call_user_func_array('static::build', func_get_args());
        Configuration::$adapter->persist($built);

        return $built;
    }

    /**
     * Find available factories and registers them for use
     *
     * This method should be invoked before using any factories.
     */
    public static function discoverFactories()
    {
        $finder = new FactoryFinder();
        FactoryRegistry::registerFactories($finder->discoverFactories());
    }
}
