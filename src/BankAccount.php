<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;
use Timedoor\TmdMidtransIris\Models\Bank;
use Timedoor\TmdMidtransIris\Models\BankAccount as BankAccountModel;
use Timedoor\TmdMidtransIris\Models\BankAccountValidated;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class BankAccount extends BaseService
{
    /**
     * Get all bank accounts
     * @throws  UnauthorizedRequestException
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
                $item       = new Arr($item);
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
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  int
     */
    public function balance(string $id)
    {
        $response = $this->apiClient->get(sprintf('/bank_accounts/%s/balance', $id));

        ConvertException::fromResponse($response);
        
        $body = new Arr($response->getBody());
        
        return $body->get('balance', 0);
    }

    /**
     * Get available bank list
     *
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  Bank[]
     */
    public function bankList()
    {
        $response = $this->apiClient->get('/beneficiary_banks');
        
        ConvertException::fromResponse($response);

        $body   = $response->getBody()['beneficiary_banks'];
        $result = [];

        if (is_array($body)) {
            foreach ($body as $item) {
                $item       = new Arr($item);
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
     * @throws  UnauthorizedRequestException|BadRequestException
     * @return  BankAccountValidated
     */
    public function validate(string $bankCode, string $accountNumber)
    {
        $response = $this->apiClient->get('/account_validation', [], [
            'bank'      => $bankCode,
            'account'   => $accountNumber
        ]);

        ConvertException::fromResponse($response);

        $body = new Arr($response->getBody());

        return (new BankAccountValidated)
                ->setId($body->get('id'))
                ->setAccountName($body->get('account_name'))
                ->setaccountNo($body->get('account_no'))
                ->setBankName($body->get('bank_name'));
    }
}