<?php

namespace Timedoor\TmdMidtransIris;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IrisTest extends TestCase
{
    public function testWithInvalidApproverKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('approver_api_key is required');

        $_ = new Iris(['api_key' => 'abc']);
    }

    public function testWithInvalidCreatorKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('creator_api_key is required');

        $_ = new Iris(['approver_api_key' => 'abc', 'api_key' => 'def']);
    }

    public function testWithInvalidMerchantKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('merchant_key is required');

        $_ = new Iris(['approver_api_key' => 'abc', 'creator_api_key' => 'def', 'key' => 'hij']);
    }

    public function testCreateIrisInstance()
    {
        $iris = new Iris([
            'approver_api_key'  => 'abc',
            'creator_api_key'   => 'def',
            'merchant_key'      => 'hij'
        ]);

        $this->assertEquals('abc', Config::$approverApiKey);
        $this->assertEquals('def', Config::$creatorApiKey);
        $this->assertEquals('hij', Config::$merchantKey);

        $this->assertInstanceOf(Iris::class, $iris);
        $this->assertInstanceOf(Beneficiary::class, $iris->beneficiary());
        $this->assertInstanceOf(Payout::class, $iris->payout());
        $this->assertInstanceOf(BankAccount::class, $iris->bankAccount());
        $this->assertInstanceOf(Transaction::class, $iris->transaction());
    }
}