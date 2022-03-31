<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\ApiClient;
use Timedoor\TmdMidtransIris\Api\IApiClient;
use Timedoor\TmdMidtransIris\BankAccount;
use Timedoor\TmdMidtransIris\Payout;

class Iris
{
    /**
     * The API Client
     *
     * @var IApiClient
     */
    private $_apiClient;

    /**
     * Beneficiary Service
     *
     * @var Beneficiary
     */
    private $_beneficiary;

    /**
     * Payout Service
     *
     * @var Payout
     */
    private $_payout;

    /**
     * Bank Account Service
     *
     * @var BankAccount
     */
    private $_bankAccount;

    public function __construct()
    {
        $this->_apiClient   = new ApiClient;

        $this->_beneficiary = new Beneficiary($this->_apiClient);
        $this->_payout      = new Payout($this->_apiClient);
        $this->_bankAccount = new BankAccount($this->_apiClient);
    }

    /**
     * Beneficiary Service
     *
     * @return Beneficiary
     */
    public function beneficiary()
    {
        return $this->_beneficiary; 
    }

    /**
     * Payout Service
     *
     * @return Payout
     */
    public function payout()
    {
        return $this->_payout; 
    }

    /**
     * Bank Account Service
     *
     * @return BankAccount
     */
    public function bankAccount()
    {
        return $this->_bankAccount; 
    }

    /**
     * Set the API Client
     *
     * @param  IApiClient  $_apiClient  The API Client
     *
     * @return  self
     */ 
    public function setApiClient(IApiClient $_apiClient)
    {
        $this->_apiClient = $_apiClient;

        return $this;
    }
}