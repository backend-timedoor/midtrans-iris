<?php

namespace Timedoor\TmdMidtransIris;

use PHPUnit\Framework\TestCase;
use Timedoor\TmdMidtransIris\Api\ApiClient;

class ApiClientTest extends TestCase
{
    protected function setUp(): void
    {
        Config::$approverApiKey = 'abc';
        Config::$creatorApiKey  = 'def';
        Config::$merchantKey    = 'xyz';
    }

    protected function tearDown(): void
    {
        Config::$approverApiKey = null;
        Config::$creatorApiKey  = null;
        Config::$merchantKey    = null;
    }

    public function testUsingCorrectAuthForCreator()
    {
        $client = new ApiClient();
        $auth   = $this->getAuthHeaderFrom($client);

        $this->assertEquals(Config::getAuthorizationKey(Actor::CREATOR), $auth);
    }

    public function testUsingCorrectAuthForApprover()
    {
        $client = new ApiClient([], Actor::APPROVER);
        $auth   = $this->getAuthHeaderFrom($client);

        $this->assertEquals(Config::getAuthorizationKey(Actor::APPROVER), $auth);
    }

    public function testActingAsUsingCorrentCredential()
    {
        $client = new ApiClient();

        $this->assertEquals(
            Config::getAuthorizationKey(Actor::CREATOR), $this->getAuthHeaderFrom($client)
        );

        $this->assertEquals(
            Config::getAuthorizationKey(Actor::APPROVER),
            $this->getAuthHeaderFrom($client->actingAs(Actor::APPROVER))
        );

        $this->assertEquals(
            Config::getAuthorizationKey(Actor::CREATOR),
            $this->getAuthHeaderFrom($client->actingAs(Actor::CREATOR))
        );
    }

    private function getAuthHeaderFrom(ApiClient $client)
    {
        $options = $client->buildHttpConfiguration('GET');

        return str_replace('Basic ', '', $options['headers']['Authorization']);
    }
}