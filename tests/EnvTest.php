<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Utils\Env;

class EnvTest extends BaseTestCase
{
    public function testSetEnv()
    {
        Env::set('IRIS_MODE', 'sandbox');

        $this->assertTrue(Env::has('IRIS_MODE'));
    }

    public function testGetEnv()
    {
        $this->assertNull(Env::get('IRIS_ENV'));

        Env::set('IRIS_ENV', 'sandbox');

        $this->assertNotNull(Env::get('IRIS_ENV'));
        $this->assertEquals('sandbox', Env::get('IRIS_ENV'));
    }
}