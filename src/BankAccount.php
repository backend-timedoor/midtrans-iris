<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Models\Bank;
use Timedoor\TmdMidtransIris\Models\BankAccountValidated;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Map;

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

        $body = new Map($response->getBody());

        return array_map(function ($item) {
            return Bank::fromArray($item);
        }, $body->get('beneficiary_banks', []));
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

        return BankAccountValidated::fromArray($response->getBody());
    }
}