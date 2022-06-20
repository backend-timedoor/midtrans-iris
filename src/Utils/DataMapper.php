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
    public abstract function mapper(): array;

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

        if (count($instance->mapper()) > 0) {
            $mapper = new Map(array_flip($instance->mapper()));

            foreach ($data as $field => $value) {
                $setter = $mapper->get($field);

                if (method_exists($instance, $setter)) {
                    call_user_func([$instance, $setter], $value);
                }
            }
        }

        return $instance;
    }
}