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

    public function apply()
    {
    }

    public function resolve(array $modifiers = [], array $overrides = [])
    {
        $this->resolveDeferreds();

        foreach ($modifiers as $modifier) {
            $this->applyModifier($modifier);
        }

        foreach ($overrides as $key => $value) {
            $this->applyOverride($key, $value);
        }
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

    private function resolveDeferreds()
    {
        foreach ($this->deferreds as $deferred) {
            $this->factory->{$deferred} = call_user_func(array($this->factory, $deferred));
        }
    }

    private function applyModifier($modifier)
    {
        call_user_func(array($this->factory, $modifier));
    }

    private function applyOverride($key, $value)
    {
        $this->factory->{$key} = $value;
    }
}
