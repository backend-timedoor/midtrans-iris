<?php

namespace Timedoor\TmdMidtransIris;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Determine wether the mocking are disabled or not
     *
     * @return boolean
     */
    public function isMockingDisabled()
    {
        return boolean(getenv('DISABLE_MOCKING')) === true;
    }

    /**
     * Disable the mocking
     *
     * @return void
     */
    public function disableMocking()
    {
        putenv('DISABLE_MOCKING=true');
    }

    /**
     * Disable the mocking
     *
     * @return void
     */
    public function enableMocking()
    {
        putenv('DISABLE_MOCKING=false');
    }
}