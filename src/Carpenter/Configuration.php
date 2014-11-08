<?php

namespace Carpenter;

/**
 * Configuration key/value store
 */
class Configuration
{
    /**
     * The adapter to use for building fixtures
     * @var Carpenter\Adapter\AdapterInterface
     */
    public static $adapter;

    /**
     * An array of paths in which to search for factories
     * @var array
     */
    public static $factoryPaths = [];
}
