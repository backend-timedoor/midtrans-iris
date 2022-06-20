<?php

namespace Timedoor\TmdMidtransIris\Facilitator;

use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\BaseTestCase;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;
use Timedoor\TmdMidtransIris\Facilitator\BankAccount;
use Timedoor\TmdMidtransIris\Models\BankAccount as BankAccountModel;
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
    public function testGetBalance()
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

        /** @var \Timedoor\TmdMidtransIris\Facilitator\BankAccount $service */
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
}