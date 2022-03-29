<?php

if (!function_exists('boolean')) {
    /**
     * Return the actual boolean of value
     *
     * @param   mixed   $value
     * @return  boolean
     */
    function boolean($value) {
        if ($value === 0 || $value === 'off' || $value === '0' || $value === null) {
           return false;
        } else if ($value === 1 || $value === 'on' || $value === '1') {
            return true;
        } else if (gettype($value) === 'boolean') {
            return $value;
        } else if ($value === 'true') {
            return true;
        } else if ($value === 'false') {
            return false;
        }

        return false;
    }
}