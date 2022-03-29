<?php

namespace Timedoor\TmdMidtransIris;

use PHPUnit\Framework\TestCase;
use Timedoor\TmdMidtransIris\Utils\Env;

class BaseTestCase extends TestCase
{
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
}