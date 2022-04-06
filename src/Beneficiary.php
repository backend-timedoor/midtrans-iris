<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class Beneficiary extends BaseService
{
    /**
     * Create a new beneficiary account
     *
     * @param   \Timedoor\TmdMidtransIris\Models\Beneficiary $data
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
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
     * @param   string                                          $alias
     * @param   \Timedoor\TmdMidtransIris\Models\Beneficiary    $data
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
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
     * 
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\Beneficiary[]
     */
    public function all()
    {
        $response = $this->apiClient->get('/beneficiaries');

        ConvertException::fromResponse($response);

        return array_map(function ($item) {
            return BeneficiaryModel::fromArray($item) ;
        }, $response->getBody() ?? []);
    }
}