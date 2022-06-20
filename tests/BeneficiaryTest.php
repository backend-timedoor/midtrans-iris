<?php

declare(strict_types=1);

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Timedoor\TmdMidtransIris\Beneficiary;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Models\Beneficiary as BeneficiaryModel;
use Timedoor\TmdMidtransIris\Utils\Json;

class BeneficiaryTest extends BaseTestCase
{
    protected $service = Beneficiary::class;

    public function testGetListBeneficiaries()
    {
        $beneficiaries = [
            (new BeneficiaryModel)
                ->setName('Mary Jane')
                ->setBank('mandiri')
                ->setAccount('1313131313')
                ->setAliasName('maryjane1')
                ->setEmail('mary.jane1@example.com')
        ];

        $createExpect   = ['status' => 'created'];
        $service        = $this->createMockService([
                            new Response(201, [], Json::encode($createExpect)),
                            new Response(200, [], Json::encode($beneficiaries)),
                        ]);

        $this->assertEquals($createExpect, $service->create($beneficiaries[0]));

        $response = $service->all();

        if (!$this->isMockingDisabled()) {
            $this->assertCount(1, $response);
        }
        
        foreach ($response as $beneficiary) {
            $this->assertInstanceOf(BeneficiaryModel::class, $beneficiary);
        }
    }

    public function testCreateBeneficiary()
    {
        $beneficiary = (new BeneficiaryModel)
                        ->setName('Mary Jane 2')
                        ->setBank('bca')
                        ->setAccount('1234567890')
                        ->setAliasName('maryjane2')
                        ->setEmail('mary.jane2@example.com');
                        
        $expect     = ['status' => 'created'];
        $service    = $this->createMockService([
                        new Response(201, [], Json::encode($expect))
                    ]);

        $this->assertEquals($expect, $service->create($beneficiary));
    }

    public function testUpdateBeneficiary()
    {
         $beneficiary = (new BeneficiaryModel)
                        ->setName('Mary jane 3')
                        ->setBank('bni')
                        ->setAccount('141414141414')
                        ->setAliasName('maryjane3')
                        ->setEmail('mary.jane3@example.com');

        $createExpect = ['status' => 'created'];
        $updateExpect = ['status' => 'updated'];

        $service = $this->createMockService([
            new Response(201, [], Json::encode($createExpect)),
            new Response(200, [], Json::encode($updateExpect)),
        ]);

        $this->assertEquals($createExpect, $service->create($beneficiary));
        $this->assertEquals(
            $updateExpect,
            $service->update(
                $beneficiary->getAliasName(), $beneficiary->setName('Jane Doe')
            )
        );
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