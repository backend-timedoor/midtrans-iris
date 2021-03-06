<?php

namespace Timedoor\TmdMidtransIris\Utils;

use ArrayAccess;

class Map
{
    /**
     * Map of attributes
     *
     * @var array
     */
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        if (is_array($attributes) || $attributes instanceof ArrayAccess) {
            $this->attributes = $attributes; 
        }
    }

    /**
     * Check if the attributes contains the given key
     *
     * @param   string  $key
     * @return  boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get the attribute value
     *
     * @param   string      $key
     * @return  mixed|null
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->attributes[$key];
    }

    /**
     * Get all values
     *
     * @return mixed
     */
    public function all()
    {
        return $this->attributes; 
    }
}