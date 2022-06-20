<?php

namespace Timedoor\TmdMidtransIris\Models;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class TopUpChannel extends DataMapper implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $vAccountType;

    /**
     * @var string
     */
    private $vAccountNumber;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'                        => $this->id,
            'virtual_account_type'      => $this->vAccountType,
            'virtual_account_number'    => $this->vAccountNumber
        ]; 
    }

    /**
     * Data mapper describe how the given data should mapped with its setter method
     *
     * @return array
     */
    public function mapper(): array
    {
        return [
            'setId'             => 'id',
            'setVAccountType'   => 'virtual_account_type',
            'setVAccountNumber' => 'virtual_account_number'
        ]; 
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of vAccountType
     *
     * @return  string
     */ 
    public function getVAccountType()
    {
        return $this->vAccountType;
    }

    /**
     * Set the value of vAccountType
     *
     * @param  string  $vAccountType
     *
     * @return  self
     */ 
    public function setVAccountType(string $vAccountType)
    {
        $this->vAccountType = $vAccountType;

        return $this;
    }

    /**
     * Get the value of vAccountNumber
     *
     * @return  string
     */ 
    public function getVAccountNumber()
    {
        return $this->vAccountNumber;
    }

    /**
     * Set the value of vAccountNumber
     *
     * @param  string  $vAccountNumber
     *
     * @return  self
     */ 
    public function setVAccountNumber(string $vAccountNumber)
    {
        $this->vAccountNumber = $vAccountNumber;

        return $this;
    }
}