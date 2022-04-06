<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\ApiClientInterface;

abstract class BaseService
{
    /**
     * Api Client
     *
     * @var \Timedoor\TmdMidtransIris\Api\ApiClientInterface
     */
    protected $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient; 
    }

    /**
     * Get api Client
     *
     * @return  \Timedoor\TmdMidtransIris\Api\ApiClientInterface
     */ 
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set api Client
     *
     * @param  \Timedoor\TmdMidtransIris\Api\ApiClientInterface  $apiClient  Api Client
     *
     * @return  self
     */ 
    public function setApiClient(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;

        return $this;
    }
}