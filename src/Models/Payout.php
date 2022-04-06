<?php

namespace Timedoor\TmdMidtransIris\Models;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class Payout extends DataMapper implements JsonSerializable
{
    /**
     * @var int
     */
    private $amount;

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
    private $beneficiaryEmail;

    /**
     * @var string
     */
    private $bank;

    /**
     * @var string
     */
    private $refNo;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $createdBy;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'amount'                => $this->amount,
            'beneficiary_name'      => $this->beneficiaryName,
            'beneficiary_account'   => $this->beneficiaryAccount,
            'beneficiary_email'     => $this->beneficiaryEmail,
            'bank'                  => $this->bank,
            'reference_no'          => $this->refNo,
            'notes'                 => $this->notes,
            'status'                => $this->status,
            'created_by'            => $this->createdBy,
            'created_at'            => $this->createdAt,
            'updated_at'            => $this->updatedAt
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
            'setAmount'             => 'amount',
            'setBeneficiaryName'    => 'beneficiary_name',
            'setBeneficiaryAccount' => 'beneficiary_account',
            'setBeneficiaryEmail'   => 'beneficiary_email',
            'setBank'               => 'bank',
            'setRefNo'              => 'reference_no',
            'setNotes'              => 'notes',
            'setStatus'             => 'status',
            'setCreatedBy'          => 'created_by',
            'setCreatedAt'          => 'created_at',
            'setUpdatedAt'          => 'updated_at',
        ]; 
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
     * Get the value of beneficiaryEmail
     *
     * @return  string
     */ 
    public function getBeneficiaryEmail()
    {
        return $this->beneficiaryEmail;
    }

    /**
     * Set the value of beneficiaryEmail
     *
     * @param  string  $beneficiaryEmail
     *
     * @return  self
     */ 
    public function setBeneficiaryEmail($beneficiaryEmail)
    {
        $this->beneficiaryEmail = $beneficiaryEmail;

        return $this;
    }

    /**
     * Get the value of bank
     *
     * @return  string
     */ 
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set the value of bank
     *
     * @param  string  $bank
     *
     * @return  self
     */ 
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
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
     * Get the value of notes
     *
     * @return  string
     */ 
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set the value of notes
     *
     * @param  string  $notes
     *
     * @return  self
     */ 
    public function setNotes($notes)
    {
        $this->notes = $notes;

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
     * Get the value of createdBy
     *
     * @return  string
     */ 
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @param  string  $createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

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

    /**
     * Get the value of updatedAt
     *
     * @return  string
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param  string  $updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}