<?php

namespace Timedoor\TmdMidtransIris;

use DateTime;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Api\ApiResponse;
use Timedoor\TmdMidtransIris\Api\ApiClientInterface;
use Timedoor\TmdMidtransIris\Models\Transaction as TransactionModel;
use Timedoor\TmdMidtransIris\Transaction;
use Timedoor\TmdMidtransIris\Utils\Json;

class TransactionTest extends BaseTestCase
{
    protected $service = Transaction::class;

    public function testGetHistoryParameters()
    {
        $apiClientMock  = $this->getMockForAbstractClass(ApiClientInterface::class);
        $service        = new Transaction($apiClientMock);

        $fromDate   = new DateTime('2022-04-05');
        $toDate     = new DateTime('2022-04-10');

        $data = [
            TransactionModel::fromArray([
                'reference_no'          => '1e4d9943929504',
                'beneficiary_name'      => 'Test Benefeciary',
                'beneficiary_account'   => '7202',
                'account'               => 'PT. Bank Central Asia Tbk.',
                'type'                  => 'Payout',
                'amount'                => '45000.00',
                'status'                => 'debit',
                'created_at'            => '2022-04-05T17:00:00Z'
            ])
        ];

        $apiClientMock
            ->expects($this->any())
            ->method('get')
            ->with(
                '/statements',
                [],
                [
                    'from_date' => $fromDate->format('Y-m-d'),
                    'to_date'   => $toDate->format('Y-m-d')
                ]
            )
            ->will($this->returnValue(
                new ApiResponse(
                    200,
                    null,
                    Json::decode(Json::encode($data)))
                )
            );

        $response = $service->history($fromDate, $toDate);

        $this->assertEquals($data, $response);
    }

    public function testGetHistory()
    {
        $data = [
            TransactionModel::fromArray([
                'reference_no'          => 'fsnbkmt1fx8q2t3ur7',
                'beneficiary_name'      => 'Test Beneficiary',
                'beneficiary_account'   => '1414141414',
                'account'               => 'PT. BANK CENTRAL ASIA TBK.',
                'type'                  => 'Refund',
                'amount'                => '33033.0',
                'status'                => 'credit',
                'created_at'            => '2022-04-01T07:13:49Z'
            ]),
            TransactionModel::fromArray([
                'reference_no'          => 'fsnbkmt1fx8q2t3ur7',
                'beneficiary_name'      => 'Test Beneficiary',
                'beneficiary_account'   => '1414141414',
                'account'               => 'PT. BANK CENTRAL ASIA TBK.',
                'type'                  => 'Refund',
                'amount'                => '0.0',
                'status'                => 'credit',
                'created_at'            => '2022-04-01T07:13:49Z'
            ]),
        ];

        $service = $this->createMockService([
            new Response(200, [], Json::encode($data))
        ]);

        $response = $service->history();

        $this->assertIsArray($response);

        if (!$this->isMockingDisabled()) {
            $this->assertEquals($data, $response);
        } else {
            if (count($response) > 0) {
                $this->assertInstanceOf(TransactionModel::class, $response[0]);
            }
        }
    }
}