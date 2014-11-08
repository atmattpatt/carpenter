<?php

namespace Carpenter\Adapter;

/**
 * Interface for fixture building adapters
 */
interface AdapterInterface
{
    /**
     * Builds a fixture
     *
     * @param string $targetClass The name of the class to build
     * @param mixed $resolved A factory with all properties resolved
     * @return mixed An instance of $targetClass
     */
    public function build($targetClass, $resolved);

    /**
     * Persists a fixture to a data store
     *
     * @param mixed $built A built fixture
     */
    public function persist($built);

    /**
     * Begins a transaction for persisted fixtures
     */
    public function beginTransaction();

    /**
     * Rolls back a transaction for persisted fixtures
     */
    public function rollback();
}
