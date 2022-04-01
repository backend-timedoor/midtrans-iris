<?php

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Models\Bank;
use Timedoor\TmdMidtransIris\BankAccount;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;
use Timedoor\TmdMidtransIris\Models\BankAccount as BankAccountModel;
use Timedoor\TmdMidtransIris\Models\BankAccountValidated;
use Timedoor\TmdMidtransIris\Utils\Json;

class BankAccountTest extends BaseTestCase
{
    protected $service = BankAccount::class;

    /**
     * THIS TEST REQUIRES AN IRIS ACCOUNT WITH A "FACILITATOR MODEL"
     * Test get a list of bank accounts
     *
     * @return void
     */
    public function testGetListOfBankAccounts()
    {
        $bankAccounts = [
            (new BankAccountModel)
                ->setId('mandiri38fd1f0e')
                ->setBankName('mandiri')
                ->setAccountName('John Doe')
                ->setAccountNumber('189873746743')
                ->setStatus('in_progress'),
            (new BankAccountModel)
                ->setId('danamon64036485')
                ->setBankName('danamon')
                ->setAccountName('John Snow')
                ->setAccountNumber('77975396492')
                ->setStatus('live')
        ];

        /** @var BankAccount $service */
        $service = $this->createMockService([
            new Response(200, [], Json::encode($bankAccounts))
        ]);

        // this will always fails except running the test with a "facilitator" model
        if ($this->isMockingDisabled()) {
            $this->expectException(UnauthorizedRequestException::class);
        }

        $response = $service->all();

        if (!$this->isMockingDisabled()) {
            $this->assertEquals($bankAccounts, $response);
        } else {
            // NOTE: to make an integration test, you need to use a "facilitator" model account
        }
    }

    /**
     * THIS TEST REQUIRES AN IRIS ACCOUNT WITH A "FACILITATOR MODEL"
     * Test get a list of bank accounts
     *
     * @return void
     */
    public function testGetBankAccountBalance()
    {
        $balance        = 1000;
        $errorExpect    = [
            'error_message' => 'You are not authorized to perform this action',
            'errors'        =>  'You are not authorized to perform this action'
        ];
        
        /** @var BankAccount */
        $service = $this->createMockService([
            new Response(200, [], Json::encode(['balance' => $balance]))
        ]);
        
        // this will always fail because it must use the "facilitator" account
        try {
            $response = $service->balance('abc');
            
            $this->assertEquals($balance, $response);
        } catch (UnauthorizedRequestException $e) {
            $this->assertEquals(401, $e->getCode());
            $this->assertEquals($errorExpect['error_message'], $e->getMessage());
            $this->assertEquals($errorExpect['errors'], $e->getErrors());
        }
    }

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
                        ->setaccountNo('0011223344');

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
            $bankAccount->getBankName(), $bankAccount->getaccountNo()
        );

        // manually set the id, because the id from the api are always different for every request
        $this->assertEquals($bankAccount->setId($response->getId()), $response);
    }
}