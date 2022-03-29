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
        Config::$apiKey = 'abcdef';
        $authkey = Config::getAuthorizationKey();
        $decoded = base64_decode($authkey);

        // auth key should contain a semicolon (:) at the end
        $this->assertEquals(strlen($decoded) - 1, strpos($decoded, ':'));
        $this->assertCount(2, explode(':', $decoded));
    }
}