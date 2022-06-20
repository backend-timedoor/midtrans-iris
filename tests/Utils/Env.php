<?php

namespace Timedoor\TmdMidtransIris\Utils;

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
        return static::get($key) !== null;
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
        $value = getenv($key, true);

        if (!$value) {
            return $default;
        }

        return $value;
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
}