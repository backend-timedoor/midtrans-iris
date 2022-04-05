<?php

namespace Timedoor\TmdMidtransIris\Utils;

abstract class DataMapper
{
    /**
     * Returns an array of mapper. This method should return an array consist of
     * A Key: which is the setter method, and
     * A Value: the value should be the corresponding array key
     * 
     * @example ['setId' => 'id'] This will get the `id` from the array and use the `setId` method of the child class
     *
     * @return array
     */
    protected abstract function getMapper(): array;

    /**
     * Create a new instance of the child class
     *
     * @return static
     */
    public static function getInstance()
    {
        return new static; 
    }

    /**
     * Map the given array with the class properties
     *
     * @param   array $data
     * @return  static
     */
    public static function fromArray(array $data)
    {
        $instance = static::getInstance(); 

        if (count($instance->getMapper()) > 0) {
            $data = new Map($data);

            foreach ($instance->getMapper() as $key => $value) {
                call_user_func([$instance, $key], $data->get($value));
            }
        }

        return $instance;
    }
}