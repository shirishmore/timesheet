<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function assertRequestOk()
    {
        return $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function assertViewReceives($prop,$val = null)
    {
        $response = $this->client->getResponse();
        $prop = $response->original->prop;

        if($val)
        {
            return $this->assertEquals($val,$prop);
        }
        $this->assertTrue(!! $prop);
    }
}
