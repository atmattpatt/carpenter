<?php

namespace Carpenter;

class Template
{
    private $factory;

    private $deferreds = [];

    private $modifiers = [];

    private $targetClass;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function apply($adapter, $resolved)
    {
        return $adapter->build($this->targetClass, $resolved);
    }

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

    public function addDeferred($deferred)
    {
        $this->deferreds[] = $deferred;
    }

    public function addModifier($modifier)
    {
        $this->modifiers[] = $modifier;
    }

    public function setTargetClass($target)
    {
        $this->targetClass = $target;
    }

    private function resolveDeferreds($factory)
    {
        foreach ($this->deferreds as $deferred) {
            $factory->{$deferred} = call_user_func(array($this->factory, $deferred));
        }
    }

    private function applyModifier($factory, $modifier)
    {
        call_user_func(array($factory, $modifier));
    }

    private function applyOverride($factory, $key, $value)
    {
        $factory->{$key} = $value;
    }
}
