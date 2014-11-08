<?php

namespace Carpenter;

/**
 * Template for building fixtures
 */
class Template
{
    /**
     * An instance of the factory
     * @var mixed
     */
    private $factory;

    /**
     * An array of methods which act as deferred properties
     * @var array
     */
    private $deferreds = [];

    /**
     * An array of methods which act as modifiers
     * @var array
     */
    private $modifiers = [];

    /**
     * The class name of the fixture to be built
     * @var string
     */
    private $targetClass;

    /**
     * Constructs a new template
     * 
     * @param mixed $factory An instance of the factory
     */
    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    /**
     * Applies the resolved properties to an adapter
     *
     * @param Carpenter\Adapter\AdapterInterface $adapter The adapter responsible for building the fixture
     * @param mixed $resolved A factory with all properties resolved
     * @return mixed A built fixture
     */
    public function apply($adapter, $resolved)
    {
        return $adapter->build($this->targetClass, $resolved);
    }

    /**
     * Resolves all of the properties of the fixture
     *
     * @param array $modifiers A list of modifiers to resolve
     * @param array $overrides A key/value map of property overrides
     * @return mixed An instance of the factory with all properties resolved
     */
    public function resolve(array $modifiers = [], array $overrides = [])
    {
        $resolved = clone $this->factory;

        $this->resolveDeferreds($resolved);

        foreach ($modifiers as $modifier) {
            $this->applyModifier($resolved, $modifier);
        }

        foreach ($overrides as $key => $value) {
            $this->applyOverride($resolved, $key, $value);
        }

        return $resolved;
    }

    /**
     * Adds a deferred property
     *
     * @param string $deferred The name of a method which acts as a deferred property
     */
    public function addDeferred($deferred)
    {
        $this->deferreds[] = $deferred;
    }

    /**
     * Adds a modifier
     *
     * @param string $modifier The name of a method which acts as a modifier
     */
    public function addModifier($modifier)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * Sets the target class of the fixture to build
     *
     * @param string $target The name of the fixture class
     */
    public function setTargetClass($target)
    {
        $this->targetClass = $target;
    }

    /**
     * Resolves all deferred properties
     *
     * @param mixed $factory The factory instance being resolved
     */
    private function resolveDeferreds($factory)
    {
        foreach ($this->deferreds as $deferred) {
            $factory->{$deferred} = call_user_func(array($this->factory, $deferred));
        }
    }

    /**
     * Resolves a given modifier
     *
     * @param mixed $factory The factory instance being resolved
     * @param string $modifier The name of a method which acts as a modifier
     */
    private function applyModifier($factory, $modifier)
    {
        call_user_func(array($factory, $modifier));
    }

    /**
     * Applies an override property
     *
     * @param mixed $factory The factory instance being resolved
     * @param string $key The name of the property to override
     * @param mixed $value The new value of the property being overridden
     */
    private function applyOverride($factory, $key, $value)
    {
        $factory->{$key} = $value;
    }
}
