<?php

namespace Timedoor\TmdMidtransIris\Aggregator;

use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Aggregator\BankAccount;
use Timedoor\TmdMidtransIris\BaseTestCase;
use Timedoor\TmdMidtransIris\Utils\Json;

class BankAccountTest extends BaseTestCase
{
    protected $service = BankAccount::class;

    public function testGetBalance()
    {
        $balance = 10000; 
        $service = $this->createMockService([
            new Response(200, [], Json::encode(['balance' => 10000]))
        ]);

        if (!$this->isMockingDisabled()) {
            $this->assertEquals($balance, $service->balance());
        } else {
            $this->assertNotNull($service->balance());
        }
    }
}