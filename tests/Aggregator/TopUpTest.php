<?php

namespace Timedoor\TmdMidtransIris\Aggregator;

use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Aggregator\TopUp;
use Timedoor\TmdMidtransIris\BaseTestCase;
use Timedoor\TmdMidtransIris\Models\TopUpChannel;
use Timedoor\TmdMidtransIris\Utils\Json;

class TopUpTest extends BaseTestCase
{
    protected $service = TopUp::class;

    public function testGetTopUpChannels()
    {
        $data = [
            TopUpChannel::fromArray([
                'id'                        => 1,
                'virtual_account_type'      => 'mandiri_bill_key',
                'virtual_account_number'    => '55566677788'
            ]),
            TopUpChannel::fromArray([
                'id'                        => 2,
                'virtual_account_type'      => 'bni_virtual_account_number',
                'virtual_account_number'    => '1213141516'
            ]),
        ];

        /** @var \Timedoor\TmdMidtransIris\Aggregator\TopUp */
        $service = $this->createMockService([
            new Response(200, [], Json::encode($data))
        ]);

        $response = $service->channels();

        $this->assertIsArray($response);

        foreach ($response as $item) {
            $this->assertInstanceOf(TopUpChannel::class, $item);
        }
        
        if (!$this->isMockingDisabled()) {
            $this->assertEquals(count($data), count($response));
        }
    }
}