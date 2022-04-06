<?php

namespace Timedoor\TmdMidtransIris\Dto;

use JsonSerializable;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

final class PayoutNotification extends DataMapper implements JsonSerializable
{
    /**
     * @var string
     */
    private $refNo;

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
    private $updatedAt;

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var string
     */
    private $errorMsg;

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'reference_no'  => $this->refNo,
            'amount'        => $this->amount,
            'status'        => $this->status,
            'updated_at'    => $this->updatedAt,
            'error_code'    => $this->errorCode,
            'error_message' => $this->errorMsg
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
            'setRefNo'      => 'reference_no',
            'setAmount'     => 'amount',
            'setStatus'     => 'status',
            'setUpdatedAt'  => 'updated_at',
            'setErrorCode'  => 'error_code',
            'setErrorMsg'   => 'error_message',
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

    /**
     * Get the value of errorCode
     *
     * @return  string
     */ 
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set the value of errorCode
     *
     * @param  string  $errorCode
     *
     * @return  self
     */ 
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get the value of errorMsg
     *
     * @return  string
     */ 
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Set the value of errorMsg
     *
     * @param  string  $errorMsg
     *
     * @return  self
     */ 
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;

        return $this;
    }
}