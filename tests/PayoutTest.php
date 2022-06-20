<?php

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Dto\PayoutNotification;
use Timedoor\TmdMidtransIris\Dto\PayoutRequest;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Payout;
use Timedoor\TmdMidtransIris\Models\Payout as PayoutModel;
use Timedoor\TmdMidtransIris\Utils\Env;
use Timedoor\TmdMidtransIris\Utils\Json;

class PayoutTest extends BaseTestCase
{
    protected $service  = Payout::class;

    public function testCreatePayout()
    {
        $body = [
            'payouts' => [
                (new PayoutModel)->setStatus(PayoutStatus::QUEUED)->setRefNo('1d4f8423393005'),
                (new PayoutModel)->setStatus(PayoutStatus::QUEUED)->setRefNo('10438f2b393005')
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

        if (!$this->isMockingDisabled()) {
            foreach ($body['payouts'] as $key => $item) {
                $this->assertEquals($item->getRefNo(), $response[$key]->getRefNo());
                $this->assertEquals($item->getStatus(), $response[$key]->getStatus());
            }
        } else {
            $this->assertIsArray($response);

            foreach ($response as $payout) {
                $this->assertInstanceOf(PayoutModel::class, $payout);
            }
        }
    }

    public function testExceptionHandlingCreatePayout()
    {
        $expect = [
            'error_message' => 'An error occurred when creating payouts',
            'errors'        => [
                [
                    'Amount is not a number',
                    'Beneficiary bank is not included in the list'
                ]
            ]
        ];

        $service = $this->createMockService([
            new Response(400, [], Json::encode($expect))
        ]);

        $payouts = [
            (new PayoutRequest)
                ->setBeneficiaryName('Example')
                ->setBeneficiaryAccount('1212121212')
                ->setBeneficiaryBank('abc')
                ->setAmount('abc')
                ->setNotes('just an example payout')
        ];

        try {
            $service->create($payouts);
        } catch (BadRequestException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals($expect['error_message'], $e->getMessage());
            $this->assertEquals($expect['errors'], $e->getErrors());
        }
    }

    public function testApprovePayout()
    {
        $errorExpect = [
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
                new Response(400, [], Json::encode($errorExpect))
            ),
            new Response(202, [], Json::encode($successExpect))
        ], Actor::APPROVER);

        try {
            $service->approve(['10438f2b393005']);
        } catch (BadRequestException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals($errorExpect['error_message'], $e->getMessage());
            $this->assertEquals($errorExpect['errors'], $e->getErrors());
        }

        // this is just a dummy otp code
        $otp = '540067';

        // for integration test on "approval" you need an actual otp
        // for more information, see: https://iris-docs.midtrans.com/#approve-payouts
        if ($this->isMockingDisabled()) {
            // if you run the integration test but don't specify the OTP, then it will fail
            if (!Env::has('OTP')) {
                $this->expectException(BadRequestException::class);
                $this->expectExceptionMessage($errorExpect['error_message']);
            }

            $otp = Env::get('OTP');
        }

        $dummyPayout    = $this->createDummyPayout()[0];
        $response       = $service->approve([$dummyPayout->getRefNo()], $otp);

        $this->assertEquals($successExpect, $response);
    }

    public function testRejectPayout()
    {
        $errorExpect = [
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
                new Response(400, [], Json::encode($errorExpect))
            ),
            new Response(202, [], Json::encode($successExpect))
        ], Actor::APPROVER);

        try {
            $service->reject(['10438f2b393005'], 'reject test payout');
        } catch (BadRequestException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals($errorExpect['error_message'], $e->getMessage());
            $this->assertEquals($errorExpect['errors'], $e->getErrors());
        }

        $dummyPayout    = $this->createDummyPayout()[0];
        $response       = $service->reject([$dummyPayout->getRefNo()], 'invalid payout amount');

        $this->assertEquals($successExpect, $response);
    }

    public function testGetPayoutDetails()
    {
        $expect = [
            'amount'                => '200000.00',
            'beneficiary_name'      => 'Peter Parker',
            'beneficiary_account'   => '1213141516',
            'beneficiary_email'     => 'peter@example.com',
            'bank'                  => 'Bank Central Asia ( BCA )',
            'reference_no'          => '83hgf882',
            'notes'                 => 'just for fun',
            'status'                => 'queued',
            'created_by'            => 'John Doe',
            'created_at'            => '2022-03-29T00:00:00Z',
            'updated_at'            => '2022-03-29T00:00:00Z'
        ];

        $service = $this->createMockService([
            new Response(200, [], Json::encode($expect))
        ]);

        $dummyPayout    = $this->createDummyPayout()[0];
        $response       = $service->get($dummyPayout->getRefNo());

        if (!$this->isMockingDisabled()) {
            $this->assertEquals($expect, $response->jsonSerialize());
        } else {
            $this->assertInstanceOf(PayoutModel::class, $response);
        }
    }

    public function testValidateNotification()
    {
        $notification = (new PayoutNotification)
                            ->setRefNo('21b6d59f9414b2636b')
                            ->setAmount('10000')
                            ->setStatus(PayoutStatus::FAILED)
                            ->setUpdatedAt('2022-03-31T11:38:12Z')
                            ->setErrorCode('002')
                            ->setErrorMsg('Invalid destination account number');

        $service    = $this->createMockService();
        $actualSig  = $service->createNotificationSignature($notification);

        $this->assertTrue($service->validateNotification($actualSig, $notification));
        $this->assertFalse($service->validateNotification('abc', $notification));

        $this->assertFalse(
            $service->validateNotification($actualSig, $notification->setAmount('5000'))
        );
    }

    protected function createDummyPayout()
    {
        $payout = (new PayoutModel)
                    ->setAmount(random_int(10000, 50000))
                    ->setBeneficiaryName('Peter Parker')
                    ->setBeneficiaryAccount('1213141516')
                    ->setBeneficiaryEmail('peter.parker@example.com')
                    ->setBank('Bank Central Asia (BCA)')
                    ->setRefNo('kkkjp25fv2gs2fgfa0')
                    ->setNotes('just a dummy payout')
                    ->setStatus(PayoutStatus::QUEUED)
                    ->setCreatedAt('Administrator')
                    ->setCreatedAt('2022-03-29T00:00:00Z')
                    ->setUpdatedAt('2022-03-29T00:00:00Z');

        $service = $this->createMockService([
            new Response(201, [], Json::encode([
                'payouts' => [$payout]
            ]))
        ]);

        return $service->create([
                (new PayoutRequest)
                    ->setBeneficiaryName('Test Beneficiary')
                    ->setBeneficiaryAccount('1414141414')
                    ->setBeneficiaryEmail('beneficiary.test@example.com')
                    ->setBeneficiaryBank('bca')
                    ->setAmount(random_int(10000, 50000))
                    ->setNotes('just a dummy payout')
        ]);
    }
}