<?php

namespace Timedoor\TmdMidtransIris\Dto;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class PayoutRequest extends DataMapper implements JsonSerializable
{
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
    private $beneficiaryBank;

    /**
     * @var string
     */
    private $beneficiaryEmail;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $notes;

    /**
     * This value is currently not used
     *
     * @var string
     */
    private $bankAccountId;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'beneficiary_name'      => $this->beneficiaryName,
            'beneficiary_account'   => $this->beneficiaryAccount,
            'beneficiary_bank'      => $this->beneficiaryBank,
            'beneficiary_email'     => $this->beneficiaryEmail,
            'amount'                => $this->amount,
            'notes'                 => $this->notes,
            'bank_account_id'       => $this->bankAccountId
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
            'setBeneficiaryName'    => 'beneficiary_name',
            'setBeneficiaryAccount' => 'beneficiary_account',
            'setBeneficiaryBank'    => 'beneficiary_bank',
            'setBeneficiaryEmail'   => 'beneficiary_email',
            'setAmount'             => 'amount',
            'setNotes'              => 'notes',
            'setBankAccountId'      => 'bank_account_id',
        ];
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
     * Get the value of beneficiaryBank
     *
     * @return  string
     */ 
    public function getBeneficiaryBank()
    {
        return $this->beneficiaryBank;
    }

    /**
     * Set the value of beneficiaryBank
     *
     * @param  string  $beneficiaryBank
     *
     * @return  self
     */ 
    public function setBeneficiaryBank($beneficiaryBank)
    {
        $this->beneficiaryBank = $beneficiaryBank;

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
     * Get this value is currently not used
     *
     * @return  string
     */ 
    public function getBankAccountId()
    {
        return $this->bankAccountId;
    }

    /**
     * Set this value is currently not used
     *
     * @param  string  $bankAccountId  This value is currently not used
     *
     * @return  self
     */ 
    public function setBankAccountId($bankAccountId)
    {
        $this->bankAccountId = $bankAccountId;

        return $this;
    }
}