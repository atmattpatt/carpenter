<?php

namespace Carpenter\Adapter;

use ReflectionClass;

/**
 * Adapter to build array fixtures
 */
class ArrayAdapter implements AdapterInterface
{
    /**
     * Builds an array
     *
     * @param string $_ ignored; no target class is instantiated
     * @param mixed $resolved A factory with all properties resolved
     * @return array
     */
    public function build($_, $resolved)
    {
        $reflection = new ReflectionClass($resolved);
        $output = [];

        foreach ($reflection->getProperties() as $property) {
            $output[$property->name] = $property->getValue($resolved);
        }

        return $output;
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function persist($built)
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function beginTransaction()
    {
    }

    /**
     * Not used
     * @codeCoverageIgnore
     */
    public function rollback()
    {
    }
}
