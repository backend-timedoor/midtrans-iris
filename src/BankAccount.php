<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Models\Bank;
use Timedoor\TmdMidtransIris\Models\BankAccountValidated;
use Timedoor\TmdMidtransIris\Utils\Map;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class BankAccount extends BaseService
{
    /**
     * Get available bank list
     *
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\Bank[]
     */
    public function bankList()
    {
        $response = $this->apiClient->get('/beneficiary_banks');
        
        ConvertException::fromResponse($response);

        $body   = $response->getBody()['beneficiary_banks'];
        $result = [];

        if (is_array($body)) {
            foreach ($body as $item) {
                $item       = new Map($item);
                $result[]   = (new Bank)
                                ->setCode($item->get('code'))
                                ->setName($item->get('name'));
            }
        }

        return $result;
    }

    /**
     * Validate the given bank account
     *
     * @param   string $bankCode
     * @param   string $accountNumber
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\BankAccountValidated
     */
    public function validate(string $bankCode, string $accountNumber)
    {
        $response = $this->apiClient->get('/account_validation', [], [
            'bank'      => $bankCode,
            'account'   => $accountNumber
        ]);

        ConvertException::fromResponse($response);

        $body = new Map($response->getBody());

        return (new BankAccountValidated)
                ->setId($body->get('id'))
                ->setAccountName($body->get('account_name'))
                ->setaccountNo($body->get('account_no'))
                ->setBankName($body->get('bank_name'));
    }
}