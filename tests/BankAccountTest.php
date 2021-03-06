<?php

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Models\Bank;
use Timedoor\TmdMidtransIris\BankAccount;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Models\BankAccountValidated;
use Timedoor\TmdMidtransIris\Utils\Json;

class BankAccountTest extends BaseTestCase
{
    protected $service = BankAccount::class;

    public function testGetBankList()
    {
        $banks = [
            (new Bank)->setCode('bca')->setName('PT. BANK CENTRAL ASIA TBK.'),
            (new Bank)->setCode('bni')->setName('PT. BANK NEGARA INDONESIA (PERSERO)'),
            (new Bank)->setCode('bri')->setName('PT. BANK RAKYAT INDONESIA (PERSERO)'),
            (new Bank)->setCode('danamon')->setName('PT. BANK DANAMON INDONESIA TBK.')
        ];

        /** @var BankAccount $service */
        $service = $this->createMockService([
            new Response(200, [], Json::encode([
                'beneficiary_banks' => $banks
            ]))
        ]);

        $response = $service->bankList();

        if (!$this->isMockingDisabled()) {
            $this->assertEquals($banks, $response);
        } else {
            $this->assertIsArray($response);
            $this->assertNotEmpty($response);
            $this->assertInstanceOf(Bank::class, $response[0]);
        }
    }

    public function testValidateBankAccount()
    {
        $bankAccount = (new BankAccountValidated)
                        ->setBankName('bca')
                        ->setAccountName('BCA Simulator A')
                        ->setAccountNo('0011223344');

        $errorExpect = [
            'id'            => 'e2c60cbd3c7a453bbc843b1f2b2e9025',
            'error_message' => 'An error occured when doing account validation',
            'errors'        => [
                'account'   => [
                    'Account does not exist'
                ]
            ]
        ];

        /** @var BankAccount */
        $service = $this->createMockService([
            new Response(400, [], Json::encode($errorExpect)),
            new Response(200, [], Json::encode($bankAccount))
        ]);

        try {
            $service->validate('bca', '123123123');
        } catch (BadRequestException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals($errorExpect['error_message'], $e->getMessage());
            $this->assertEquals($errorExpect['errors'], $e->getErrors());
        }

        $response = $service->validate(
            $bankAccount->getBankName(), $bankAccount->getAccountNo()
        );

        // manually set the id, because the id from the api are always different for every request
        $this->assertEquals($bankAccount->setId($response->getId()), $response);
    }
}