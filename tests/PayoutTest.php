<?php

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Config;
use Timedoor\TmdMidtransIris\Dto\PayoutRequest;
use Timedoor\TmdMidtransIris\Payout;
use Timedoor\TmdMidtransIris\Utils\Dumper;
use Timedoor\TmdMidtransIris\Utils\Env;
use Timedoor\TmdMidtransIris\Utils\Json;

class PayoutTest extends BaseTestCase
{
    protected $service = Payout::class;

    protected function setUp(): void
    {
        Config::$apiKey         = Env::get('API_KEY');
        Config::$merchantKey    = Env::get('MERCHANT_KEY');
    }

    public static function tearDownAfterClass(): void
    {
        Config::$apiKey         = null;
        Config::$merchantKey    = null;
    }

    public function testCreatePayout()
    {
        $body = [
            'payouts' => [
                [
                    'status'          => 'queued',
                    'reference_no'    => '1d4f8423393005'
                ],
                [
                    'status'          => 'queued',
                    'reference_no'    => '10438f2b393005'
                ]
            ]
        ];

        $service = $this->createMockService([
            new Response(201, [], Json::encode($body))
        ]);

        $payouts = [
            (new PayoutRequest)
                ->setBeneficiaryName('Example')
                ->setBeneficiaryAccount('1212121212')
                ->setBeneficiaryBank('bca')
                ->setAmount(10000)
                ->setNotes('just an example payout'),
            (new PayoutRequest)
                ->setBeneficiaryName('Example 2')
                ->setBeneficiaryAccount('1313131313')
                ->setBeneficiaryBank('bni')
                ->setAmount(20000)
                ->setNotes('just an example payout')
        ];

        $response = $service->create($payouts);

        $this->assertEquals(201, $response->getCode());
    
        if (!$this->isMockingDisabled()) {
            $this->assertEquals($body, $response->getBody());
        } else {
            foreach ($response->getBody()['payouts'] as $payout) {
                $this->assertArrayHasKey('status', $payout);
                $this->assertArrayHasKey('reference_no', $payout);
            }
        }
    }

    public function testExceptionHandlingCreatePayout()
    {
        $body = [
            'error_message' => 'An error occurred when creating payouts',
            'errors'        => [
                [
                    'Amount is not a number',
                    'Beneficiary bank is not included in the list'
                ]
            ]
        ];

        $service = $this->createMockService([
            new Response(400, [], Json::encode($body))
        ]);

        $payouts = [
            (new PayoutRequest)
                ->setBeneficiaryName('Example')
                ->setBeneficiaryAccount('1212121212')
                ->setBeneficiaryBank('abc')
                ->setAmount('abc')
                ->setNotes('just an example payout')
        ];

        $response = $service->create($payouts);

        $this->assertEquals(400, $response->getCode());
        $this->assertEquals($body, $response->getBody());
    }

    public function testApprovePayout()
    {
        $notfoundExpect = [
            'error_message' => 'An error occurred when approving payouts',
            'errors'        => [
                'Payouts not found. Please check reference nos'
            ]
        ];

        $successExpect = ['status' => 'ok'];

        $service = $this->createMockService([
            new RequestException(
                'bad request',
                new Request('POST', 'test'),
                new Response(400, [], Json::encode($notfoundExpect))
            ),
            new Response(202, [], Json::encode($successExpect))
        ]);

        $notfoundResp = $service->approve(['10438f2b393005']);

        $this->assertEquals(400, $notfoundResp->getCode());
        $this->assertEquals($notfoundExpect, $notfoundResp->getBody());

        $successResp = $service->approve(['wfe8ck5z85bdxb21ts'], '540067');

        $this->assertEquals(202, $successResp->getCode());
        $this->assertEquals($successExpect, $successResp->getBody());
    }

    public function testRejectPayout()
    {
        $notfoundExpect = [
            'error_message' => 'An error occurred when rejecting payouts',
            'errors'        => [
                'Payouts not found. Please check reference nos'
            ]
        ];

        $successExpect = ['status' => 'ok'];

        $service = $this->createMockService([
            new RequestException(
                'Bad Request',
                new Request('POST', 'test'),
                new Response(400, [], Json::encode($notfoundExpect))
            ),
            new Response(202, [], Json::encode($successExpect))
        ]);

        $notfoundResp = $service->reject(['10438f2b393005'], 'reject test payout');

        $this->assertEquals(400, $notfoundResp->getCode());
        $this->assertEquals($notfoundExpect, $notfoundResp->getBody());

        $successResp = $service->reject(['f8ecexm43dads2am4n'], 'invalid payout amount');

        $this->assertEquals(202, $successResp->getCode());
        $this->assertEquals($successExpect, $successResp->getBody());
    }

    public function testGetPayoutDetails()
    {
        $expect = [
            "amount"                => "200000.00",
            "beneficiary_name"      => "Peter Parker",
            "beneficiary_account"   => "1213141516",
            "bank"                  => "Bank Central Asia ( BCA )",
            "reference_no"          => "83hgf882",
            "notes"                 => "just for fun",
            "status"                => "queued",
            "created_by"            => "John Doe",
            "created_at"            => "2022-03-29T00:00:00Z",
            "updated_at"            => "2022-03-29T00:00:00Z"
        ];

        $service = $this->createMockService([
            new Response(200, [], Json::encode($expect))
        ]);

        $response = $service->get('ke1zd3rez12zk0nxmb');

        $this->assertEquals(200, $response->getCode());
        
        if (!$this->isMockingDisabled()) {
            $this->assertEquals($expect, $response->getBody());
        } else {
            foreach (array_keys($expect) as $field) {
                $this->assertArrayHasKey($field, $response->getBody());
            }
        }
    }
}