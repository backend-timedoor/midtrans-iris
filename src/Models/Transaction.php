<?php

namespace Timedoor\TmdMidtransIris\Models;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class Transaction extends DataMapper implements JsonSerializable
{
    /**
     * @var string
     */
    private $refNo;

    /**
     * @var string
     */
    private $beneficiaryName;

    /**
     * @var string
     */
    private $beneficiaryAccount;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $status;
    
    /**
     * @var string
     */
    private $createdAt;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'reference_no'          => $this->refNo,
            'beneficiary_name'      => $this->beneficiaryName,
            'beneficiary_account'   => $this->beneficiaryAccount,
            'account'               => $this->account,
            'type'                  => $this->type,
            'amount'                => $this->amount,
            'status'                => $this->status,
            'created_at'            => $this->createdAt
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
            'setRefNo'              => 'reference_no',
            'setBeneficiaryName'    => 'beneficiary_name',
            'setBeneficiaryAccount' => 'beneficiary_account',
            'setAccount'            => 'account',
            'setType'               => 'type',
            'setAmount'             => 'amount',
            'setStatus'             => 'status',
            'setCreatedAt'          => 'created_at',
        ]; 
    }

    /**
     * Get the value of refNo
     *
     * @return  string
     */ 
    public function getRefNo()
    {
        return $this->refNo;
    }

    /**
     * Set the value of refNo
     *
     * @param  string  $refNo
     *
     * @return  self
     */ 
    public function setRefNo($refNo)
    {
        $this->refNo = $refNo;

        return $this;
    }

    /**
     * Get the value of beneficiaryName
     *
     * @return  string
     */ 
    public function getBeneficiaryName()
    {
        return $this->beneficiaryName;
    }

    /**
     * Set the value of beneficiaryName
     *
     * @param  string  $beneficiaryName
     *
     * @return  self
     */ 
    public function setBeneficiaryName($beneficiaryName)
    {
        $this->beneficiaryName = $beneficiaryName;

        return $this;
    }

    /**
     * Get the value of beneficiaryAccount
     *
     * @return  string
     */ 
    public function getBeneficiaryAccount()
    {
        return $this->beneficiaryAccount;
    }

    /**
     * Set the value of beneficiaryAccount
     *
     * @param  string  $beneficiaryAccount
     *
     * @return  self
     */ 
    public function setBeneficiaryAccount($beneficiaryAccount)
    {
        $this->beneficiaryAccount = $beneficiaryAccount;

        return $this;
    }

    /**
     * Get the value of account
     *
     * @return  string
     */ 
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set the value of account
     *
     * @param  string  $account
     *
     * @return  self
     */ 
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return  string
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of amount
     *
     * @return  int
     */ 
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @param  int  $amount
     *
     * @return  self
     */ 
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return  string
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param  string  $createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
