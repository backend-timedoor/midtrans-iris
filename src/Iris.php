<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\ApiClient;

class Iris
{
    /**
     * Beneficiary
     *
     * @var Beneficiary
     */
    private $_beneficiary;

    public function __construct()
    {
        $apiClient = new ApiClient;

        $this->_beneficiary = new Beneficiary($apiClient);
    }

    /**
     * Beneficiary
     *
     * @return Beneficiary
     */
    public function beneficiary()
    {
        return $this->_beneficiary; 
    }
}