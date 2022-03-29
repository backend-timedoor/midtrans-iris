<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Api\ApiResponse;
use Timedoor\TmdMidtransIris\Api\IApiClient;
use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class Beneficiary
{
    /**
     * Api Client
     *
     * @var IApiClient
     */
    private $apiClient;

    public function __construct(IApiClient $apiClient)
    {
        $this->apiClient = $apiClient; 
    }

    /**
     * Create a new beneficiary account
     *
     * @param   BeneficiaryModel $data
     * @return  ApiResponse
     */
    public function create(BeneficiaryModel $data)
    {
        $response = $this->apiClient->post('/beneficiaries', $data);

        ConvertException::fromResponse($response);
    
        return $response;
    }

    /**
     * Update existing beneficiary
     *
     * @param   string              $alias
     * @param   BeneficiaryModel    $data
     * @return  ApiResponse
     */
    public function update($alias, BeneficiaryModel $data)
    {
        $response = $this->apiClient->patch(sprintf('beneficiaries/%s', $alias), $data);

        ConvertException::fromResponse($response);

        return $response;
    }

    /**
     * Get list of beneficiaries
     *
     * @return Beneficiary[]
     */
    public function all()
    {
        $response = $this->apiClient->get('/beneficiaries');

        ConvertException::fromResponse($response);

        $body   = $response->getBody();
        $result = [];

        foreach ($body as $item) {
            $item       = new Arr($item);
            $result[]   = (new BeneficiaryModel)
                            ->setName($item->get('name'))
                            ->setBank($item->get('bank'))
                            ->setAccount($item->get('account'))
                            ->setAliasName($item->get('alias'))
                            ->setEmail($item->get('email'));
        } 

        return $result;
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