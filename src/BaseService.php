<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\IApiClient;

abstract class BaseService
{
    /**
     * Api Client
     *
     * @var \Timedoor\TmdMidtransIris\Api\IApiClient
     */
    protected $apiClient;

    public function __construct(IApiClient $apiClient)
    {
        $this->apiClient = $apiClient; 
    }

    /**
     * Get api Client
     *
     * @return  \Timedoor\TmdMidtransIris\Api\IApiClient
     */ 
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set api Client
     *
     * @param  \Timedoor\TmdMidtransIris\Api\IApiClient  $apiClient  Api Client
     *
     * @return  self
     */ 
    public function setApiClient(IApiClient $apiClient)
    {
        $this->apiClient = $apiClient;

        return $this;
    }
}