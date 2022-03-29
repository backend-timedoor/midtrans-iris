<?php

declare(strict_types=1);

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Api\ApiClient;
use Timedoor\TmdMidtransIris\Beneficiary;
use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\Dumper;
use Timedoor\TmdMidtransIris\Utils\Json;

class BeneficiaryTest extends BaseTestCase
{
    public function setUp(): void
    {
        Config::$apiKey         = $_ENV['API_KEY'];
        Config::$merchantKey    = $_ENV['MERCHANT_KEY'];
    }

    /**
     * Create mock service
     *
     * @param   array       $responses
     * @return  Beneficiary
     */
    protected function createMockService(array $responses = [])
    {
        $opts = [];

        if (count($responses) > 0 && !$this->isMockingDisabled()) {
            $opts['handler'] = HandlerStack::create(
                new MockHandler($responses)
            );
        }

        return new Beneficiary(
            new ApiClient($opts)
        );
    }

    public function testGetListBeneficiaries()
    {
        $data = [
            [
                "name"          => "John Doe",
                "bank"          => "danamon",
                "account"       => "1234567890",
                "alias_name"    => "johndanamon",
                "email"         => "john@danamnoexample.com"
            ],
            [
                "name"          => "Mary Jane",
                "bank"          => "mandiri",
                "account"       => "1232133213",
                "alias_name"    => "marymandiri1",
                "email"         => "mary@mandiriexample.com"
            ]
        ];

        $beneficiaries = [];

        foreach ($data as $item) {
            $item               = new Arr($item);
            $beneficiaries[]    = (new BeneficiaryModel)
                                    ->setName($item->get('name'))
                                    ->setBank($item->get('bank'))
                                    ->setAccount($item->get('account'))
                                    ->setAliasName($item->get('alias'))
                                    ->setEmail($item->get('email'));
        }

        $service = $this->createMockService([
            new Response(200, [], Json::encode($data)),
        ]);

        $response = $service->all();

        $this->assertCount(2, $response);
        $this->assertEquals($beneficiaries, $response);
    }

    public function testCreateBeneficiary()
    {
        $beneficiary = (new BeneficiaryModel)
                        ->setName('John Doe')
                        ->setBank('danamon')
                        ->setAccount('1234567890')
                        ->setAliasName('johndanamon')
                        ->setEmail('john@danamnoexample.com');
                        
        $service = $this->createMockService([
            new Response(201, [], Json::encode(['status' => 'created']))
        ]);

        $response = $service->create($beneficiary);

        $this->assertArrayHasKey('status', $response->getBody());
        $this->assertEquals(201, $response->getCode());
    }

    public function testUpdateBeneficiary()
    {
         $beneficiary = (new BeneficiaryModel)
                        ->setName('John Doe')
                        ->setBank('danamon')
                        ->setAccount('1234567890')
                        ->setAliasName('johndanamon')
                        ->setEmail('john@danamnoexample.com');

        $service = $this->createMockService([
            new Response(201, [], Json::encode(['status' => 'created'])),
            new Response(200, [], Json::encode(['status' => 'updated'])),
        ]);

        $createResponse = $service->create($beneficiary);

        $this->assertEquals(['status' => 'created'], $createResponse->getBody());
        $this->assertEquals(201, $createResponse->getCode());

        $response = $service->update(
            $beneficiary->getAliasName(), $beneficiary->setName('Jane Doe')
        );

        $this->assertEquals(['status' => 'updated'], $response->getBody());
        $this->assertEquals(200, $response->getCode());
    }

    public function testExceptionHandlingCreateBeneficiary()
    {
        $beneficiary = (new BeneficiaryModel)
                        ->setName('John Doe')
                        ->setBank('danamon')
                        ->setAccount('1234567890')
                        ->setAliasName('johndanamon')
                        ->setEmail('john@danamnoexample.com');

        $successBody    = ['status' => 'created'];
        $errorBody      = [
            'error_message' => 'An error occurred when creating beneficiary',
            'errors'        => [
                'Account has already been taken',
                'Alias name has already been taken'
            ]
        ];

        $service = $this->createMockService([
            new Response(201, [], Json::encode($successBody)),
            new RequestException(
                'something went wrong',
                new Request('POST', 'test'),
                new Response(400, [], Json::encode($errorBody))
            ),
        ]);

        $firstResp  = $service->create($beneficiary);
        $secondResp = $service->create($beneficiary);

        $this->assertEquals($firstResp->getCode(), 201);
        $this->assertEquals($successBody, $firstResp->getBody());

        $this->assertEquals($secondResp->getCode(), 400);
        $this->assertEquals($errorBody, $secondResp->getBody());
    }

    public function testExceptionHandlingUpdateBeneficiary()
    {
        $beneficiary = (new BeneficiaryModel)
                        ->setName('John Doe')
                        ->setBank('danamon')
                        ->setAccount('1234567890')
                        ->setAliasName('johndanamon')
                        ->setEmail('john@danamnoexample.com');

        $body = [
            'error_message' => 'An error occurred when updating beneficiary',
            'errors'        => 'Could not find saved beneficiary'
        ];

        $service = $this->createMockService([
            new RequestException(
                'something went wrong',
                new Request('PATCH', 'test'),
                new Response(404, [], Json::encode($body))
            )
        ]);

        $response = $service->update(
            $beneficiary->getAliasName(), $beneficiary->setName('Jane Doe')
        );

        $this->assertArrayHasKey('errors', $response->getBody());
        $this->assertArrayHasKey('error_message', $response->getBody());
        $this->assertEquals($body, $response->getBody());
        $this->assertEquals(404, $response->getCode());

        $body = [
            'error_message' => 'An error occurred when updating beneficiary',
            'errors' => [
                'Bank is not included in the list'
            ],
        ];

        $service = $this->createMockService([
            new Response(201, [], Json::encode(['status' => 'created'])),
            new RequestException(
                'something went wrong',
                new Request('PATCH', 'test'),
                new Response(400, [], Json::encode($body))
            )
        ]);

        $createResponse = $service->create($beneficiary);

        $this->assertEquals(201, $createResponse->getCode());
        $this->assertEquals(['status' => 'created'], $createResponse->getBody());

        $response = $service->update(
            $beneficiary->getAliasName(), $beneficiary->setBank('example_bank')
        );

        $this->assertEquals(400, $response->getCode());
        $this->assertEquals($body, $response->getBody());
    }
}