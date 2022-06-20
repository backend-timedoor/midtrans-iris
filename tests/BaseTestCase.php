<?php

namespace Timedoor\TmdMidtransIris;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Timedoor\TmdMidtransIris\Api\ApiClient;
use Timedoor\TmdMidtransIris\Utils\Env;

abstract class BaseTestCase extends TestCase
{
    /**
     * The service class
     *
     * @var mixed
     */
    protected $service;

    /**
     * Determine wether the mocking are disabled or not
     *
     * @return boolean
     */
    public function isMockingDisabled()
    {
        return boolean(Env::get('DISABLE_MOCKING')) === true;
    }

    /**
     * Disable the mocking
     *
     * @return void
     */
    public function disableMocking()
    {
        Env::set('DISABLE_MOCKING', true);
    }

    /**
     * Disable the mocking
     *
     * @return void
     */
    public function enableMocking()
    {
        Env::set('DISABLE_MOCKING', false);
    }

    protected function setUp(): void
    {
        Config::$approverApiKey = Env::get('APPROVER_API_KEY');
        Config::$creatorApiKey  = Env::get('CREATOR_API_KEY');
        Config::$merchantKey    = Env::get('MERCHANT_KEY');
    }

    protected function tearDown(): void
    {
        Config::$approverApiKey = null;
        Config::$creatorApiKey  = null;
        Config::$merchantKey    = null;
    }

    /**
     * Create mock service
     *
     * @param   array   $handlers
     * @return  mixed
     */
    protected function createMockService(array $handlers = [], $actor = Actor::CREATOR)
    {
        $opts = [];

        if (count($handlers) > 0 && !$this->isMockingDisabled()) {
            $opts['handler'] = HandlerStack::create(
                new MockHandler($handlers)
            );
        }

        $client     = new ApiClient($opts, $actor);
        $service    = $this->service;

        return new $service($client);
    }
}