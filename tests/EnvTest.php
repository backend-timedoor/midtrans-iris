<?php

namespace Timedoor\TmdMidtransIris;

use Timedoor\TmdMidtransIris\Utils\Env;

class EnvTest extends BaseTestCase
{
    public function testSetEnv()
    {
        Env::set('IRIS_MODE', 'sandbox');

        $this->assertArrayHasKey('IRIS_MODE', Env::vars()->all());
    }

    public function testGetEnv()
    {
        $this->assertNull(Env::get('IRIS_ENV'));

        Env::set('IRIS_ENV', 'sandbox');

        $this->assertNotNull(Env::get('IRIS_ENV'));
        $this->assertEquals('sandbox', Env::get('IRIS_ENV'));
    }
}