<?php

namespace Carpenter\Adapter;

use ReflectionClass;

class ArrayAdapter implements AdapterInterface
{
    public function build($_, $resolved)
    {
        $reflection = new ReflectionClass($resolved);
        $output = [];

        foreach ($reflection->getProperties() as $property) {
            $output[$property->name] = $property->getValue($resolved);
        }

        return $output;
    }

    /** @codeCoverageIgnore */
    public function persist($built)
    {
    }

    /** @codeCoverageIgnore */
    public function beginTransaction()
    {
    }

    /** @codeCoverageIgnore */
    public function rollback()
    {
    }
}
