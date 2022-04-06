<?php

namespace Timedoor\TmdMidtransIris\Models;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class BankAccountValidated extends DataMapper implements JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $bankName;

    /**
     * @var string
     */
    private $accountName;

    /**
     * @var string
     */
    private $accountNo;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'bank_account_id'   => $this->id,
            'bank_name'         => $this->bankName,
            'account_name'      => $this->accountName,
            'account_no'        => $this->accountNo
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
            'setId'             => 'bank_account_id',
            'setBankName'       => 'bank_name',
            'setAccountName'    => 'account_name',
            'setAccountNo'      => 'account_no'
        ]; 
    }

    /**
     * Get the value of id
     *
     * @return  string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of bankName
     *
     * @return  string
     */ 
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set the value of bankName
     *
     * @param  string  $bankName
     *
     * @return  self
     */ 
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get the value of accountName
     *
     * @return  string
     */ 
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set the value of accountName
     *
     * @param  string  $accountName
     *
     * @return  self
     */ 
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get the value of accountNo
     *
     * @return  string
     */ 
    public function getAccountNo()
    {
        return $this->accountNo;
    }

    /**
     * Set the value of accountNo
     *
     * @param  string  $accountNo
     *
     * @return  self
     */ 
    public function setAccountNo($accountNo)
    {
        $this->accountNo = $accountNo;

        return $this;
    }
}
