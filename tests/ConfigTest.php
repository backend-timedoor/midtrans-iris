<?php

declare(strict_types=1);

namespace Timedoor\TmdMidtransIris;

use PHPUnit\Framework\TestCase;
use Timedoor\TmdMidtransIris\Config;

class ConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        Config::$isProduction = false;
    }

    public function testConfigEnvironment()
    {
        Config::$isProduction = false;
        $this->assertEquals(Config::SANDBOX_BASE_URL, Config::getBaseUrl());

        Config::$isProduction = true; 
        $this->assertEquals(Config::PRODUCTION_BASE_URL, Config::getBaseUrl());
    }

    public function testBuildAbsoluteUrl()
    {
        $url = Config::buildUrl('/api/beneficiaries');
        $this->assertEquals(sprintf('%s/api/beneficiaries', Config::SANDBOX_BASE_URL), $url);
    }

    public function testEncodeAuthorizationKey()
    {
        Config::$approverApiKey = 'abcdef';
        Config::$creatorApiKey  = 'ghijkl';

        $this->assertEquals(
            base64_encode(sprintf('%s:', Config::$approverApiKey)),
            Config::getAuthorizationKey(Actor::APPROVER)
        );

        $this->assertEquals(
            base64_encode(sprintf('%s:', Config::$creatorApiKey)),
            Config::getAuthorizationKey(Actor::CREATOR)
        );

        $approver   = base64_decode(Config::getAuthorizationKey(Actor::APPROVER));
        $creator    = base64_decode(Config::getAuthorizationKey(Actor::CREATOR));

        $this->assertEquals(strlen($approver) - 1, strpos($approver, ':'));
        $this->assertCount(2, explode(':', $approver));

        $this->assertEquals(strlen($creator) - 1, strpos($creator, ':'));
        $this->assertCount(2, explode(':', $creator));
    }
}