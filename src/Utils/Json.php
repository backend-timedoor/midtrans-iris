<?php

namespace Timedoor\TmdMidtransIris\Utils;

use InvalidArgumentException;
use JsonSerializable;

class Json
{
    /**
     * Encode the given content to JSON string
     *
     * @param   mixed   $content
     * @return  string
     */
    public static function encode($content)
    {
        if (!is_array($content) && !$content instanceof JsonSerializable) {
            throw new InvalidArgumentException(
                'content should be the type of array or object that implements json serializable'
            );
        }

        return json_encode($content);
    }

    /**
     * Decode the given JSON to the desired result
     *
     * @param   string          $content
     * @param   boolean|null    $associative
     * @return  mixed
     */
    public static function decode($content, ?bool $associative = true)
    {
        $decoded = json_decode($content, $associative);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $content;
        }

        return $decoded;
    }

    /**
     * Check whether the given JSON string is valid
     *
     * @param   string  $content
     * @return  boolean
     */
    public static function isValid($content)
    {
        return is_array(static::decode($content, true));
    }
}