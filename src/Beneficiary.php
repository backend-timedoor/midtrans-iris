<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class Beneficiary extends BaseService
{
    /**
     * Create a new beneficiary account
     *
     * @param   BeneficiaryModel $data
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  array
     */
    public function create(BeneficiaryModel $data)
    {
        $response = $this->apiClient->post('/beneficiaries', $data);

        ConvertException::fromResponse($response);
    
        return $response->getBody();
    }

    /**
     * Update existing beneficiary
     *
     * @param   string              $alias
     * @param   BeneficiaryModel    $data
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  array
     */
    public function update($alias, BeneficiaryModel $data)
    {
        $response = $this->apiClient->patch(sprintf('beneficiaries/%s', $alias), $data);

        ConvertException::fromResponse($response);

        return $response->getBody();
    }

    /**
     * Get list of beneficiaries
     * @throws  UnauthorizedRequestException
     * @return  Beneficiary[]
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
}