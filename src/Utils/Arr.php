<?php

namespace Timedoor\TmdMidtransIris\Utils;

class Arr
{
    /**
     * Array of attributes
     *
     * @var array
     */
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes; 
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
    public function get($key)
    {
        return $this->has($key) ? $this->attributes[$key] : null;
    }
}