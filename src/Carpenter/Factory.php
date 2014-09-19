<?php

namespace Carpenter;

class Factory
{
    public static function build()
    {
        $arguments = func_get_args();
        $traits    = [];
        $overrides = [];

        $factory = array_shift($arguments);
        if (count($arguments) > 1) {
            $overrides = array_pop($arguments);
            $traits = $arguments;
        } elseif (count($arguments) > 0 && is_array($arguments[0])) {
            $overrides = $arguments;
        } elseif (count($arguments) > 0 && is_string($arguments[0])) {
            $traits = $arguments;
        }

        printf("Building %s with traits %s and overrides %s", $factory, var_export($traits, true), var_export($overrides, true));
    }
}
