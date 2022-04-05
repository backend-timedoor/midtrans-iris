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
     * @return  BankAccountModel[]
     */
    public function all()
    {
        $response = $this->apiClient->get('/bank_accounts');

        ConvertException::fromResponse($response);

        $body   = $response->getBody();
        $result = [];

        if (is_array($body)) {
            foreach ($body as $item) {
                $item       = new Map($item);
                $result[]   = (new BankAccountModel)
                                ->setId($item->get('bank_account_id'))
                                ->setBankName($item->get('bank_name'))
                                ->setAccountName($item->get('account_name'))
                                ->setAccountNumber($item->get('account_number'))
                                ->setStatus($item->get('status'));
            }
        }

        return $result;
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