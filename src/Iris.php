<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\ApiClient;
use Timedoor\TmdMidtransIris\Payout;

class Iris
{
    /**
     * Beneficiary
     *
     * @var Beneficiary
     */
    private $_beneficiary;

    private $_payout;

    public function __construct()
    {
        $apiClient = new ApiClient;

        $this->_beneficiary = new Beneficiary($apiClient);
        $this->_payout      = new Payout($apiClient);
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
}