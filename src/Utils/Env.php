<?php

namespace Timedoor\TmdMidtransIris\Utils;

use Timedoor\TmdMidtransIris\Utils\Map;

class Env
{
    /**
     * Determine if environment key exists
     *
     * @param   string  $key
     * @return  boolean
     */
    public static function has($key)
    {
        return static::vars()->has($key);
    }

    /**
     * Get environment value
     *
     * @param   string      $key
     * @param   mixed       $default
     * @return  mixed|null
     */
    public static function get($key, $default = null)
    {
        return static::vars()->get($key, $default);
    }

    /**
     * Set new environment
     *
     * @param   string $key
     * @param   string $value
     * @return   void
     */
    public static function set($key, $value)
    {
        putenv(sprintf("%s=%s", $key, $value));
    }

    /**
     * Get all environment variables
     *
     * @return Map
     */
    public static function vars()
    {
        return new Map(getenv()); 
    }
}