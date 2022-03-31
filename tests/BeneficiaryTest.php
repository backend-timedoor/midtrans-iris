<?php

declare(strict_types=1);

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Beneficiary;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\Arr;
use Timedoor\TmdMidtransIris\Utils\Env;
use Timedoor\TmdMidtransIris\Utils\Json;

class BeneficiaryTest extends BaseTestCase
{
    protected $service = Beneficiary::class;

    public function setUp(): void
    {
        Config::$apiKey         = Env::get('API_KEY');
        Config::$merchantKey    = Env::get('MERCHANT_KEY');
    }

    public static function tearDownAfterClass(): void
    {
        Config::$apiKey         = null;
        Config::$merchantKey    = null;
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
                        ->setAccount('abc')
                        ->setAliasName('johndanamon')
                        ->setEmail('john@danamnoexample.com');

        $expect = [
            'error_message' => 'An error occurred when creating beneficiary',
            'errors' => [
                'Account is too short (minimum is 6 characters)',
                'Account is not a number'
            ]
        ];

        $service = $this->createMockService([
            new RequestException(
                'something went wrong',
                new Request('POST', 'test'),
                new Response(400, [], Json::encode($expect))
            ),
        ]);

        try {
            $service->create($beneficiary);
        } catch (BadRequestException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertEquals($expect['error_message'], $e->getMessage());
            $this->assertEquals($expect['errors'], $e->getErrors());
        }
    }

    public function testExceptionHandlingUpdateBeneficiary()
    {
        $beneficiary = (new BeneficiaryModel)
                        ->setName('John Doe')
                        ->setBank('danamon')
                        ->setAccount('1234567890')
                        ->setAliasName('anonymous')
                        ->setEmail('john@danamnoexample.com');

        $expect = [
            'error_message' => 'An error occurred when updating beneficiary',
            'errors'        => 'Could not find saved beneficiary'
        ];

        $service = $this->createMockService([
            new RequestException(
                'something went wrong',
                new Request('PATCH', 'test'),
                new Response(404, [], Json::encode($expect))
            )
        ]);

        try {
            $service->update(
                $beneficiary->getAliasName(), $beneficiary->setName('Jane Doe')
            );
        } catch (BadRequestException $e) {
            $this->assertEquals(404, $e->getCode());
            $this->assertEquals($expect['error_message'], $e->getMessage());
            $this->assertEquals($expect['errors'], $e->getErrors());
        }
    }
}