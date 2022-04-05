<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\IApiClient;

abstract class BaseService
{
    /**
     * Api Client
     *
     * @var IApiClient
     */
    protected $apiClient;

    public function __construct(IApiClient $apiClient)
    {
        $this->apiClient = $apiClient; 
    }

    /**
     * Get api Client
     *
     * @return  IApiClient
     */ 
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set api Client
     *
     * @param  IApiClient  $apiClient  Api Client
     *
     * @return  self
     */ 
    public function setApiClient(IApiClient $apiClient)
    {
        $this->apiClient = $apiClient;

        return $this;
    }
}