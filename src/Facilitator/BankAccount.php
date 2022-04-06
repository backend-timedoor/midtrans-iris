<?php

namespace Timedoor\TmdMidtransIris\Facilitator;

use Timedoor\TmdMidtransIris\BaseService;
use Timedoor\TmdMidtransIris\Models\BankAccount as BankAccountModel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Map;

class BankAccount extends BaseService
{
    /**
     * Get all bank accounts
     * 
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\BankAccount[]
     */
    public function all()
    {
        $response = $this->apiClient->get('/bank_accounts');

        ConvertException::fromResponse($response);

        return array_map(function ($item) {
            return BankAccountModel::fromArray($item);
        }, $response->getBody() ?? []);
    }

    /**
     * Get balance of a certain bank account
     *
     * @param   string $id
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  int
     */
    public function balance(string $id)
    {
        $response = $this->apiClient->get(sprintf('/bank_accounts/%s/balance', $id));

        ConvertException::fromResponse($response);
        
        $body = new Map($response->getBody());
        
        return $body->get('balance', 0);
    }
}