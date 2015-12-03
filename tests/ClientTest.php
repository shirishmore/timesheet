<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Client;

class ClientTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $response = $this->call('GET', 'clients');
        //$this->assertEquals(200, $response->getStatusCode());
        $actual = $response->getStatusCode();
        $this->assertTrue($response->isOk(), 'Expected status code 200, got ' .$actual);
        $this->assertRequestOk();
        $this->assertViewReceives('clients');
        //$this->assertTrue(!! $response->getContent()->clients);
        //$this->assertEquals(200, $response->status());

       // $this->assertViewHas('clients');

    }

    public function testShow()
    {
        $response = $this->call('GET', 'clients/1');
        $this->assertTrue($response->isOk());
    }

    public function testCreate()
    {
        $response = $this->call('GET', 'clients/create');
        $this->assertTrue($response->isOk());
    }

    public function testEdit()
    {
        $response = $this->call('GET', 'clients/1/edit');
        $this->assertTrue($response->isOk());
    }

    public function testStore()
    {
        $response = $this->call('POST', 'clients');
        $this->assertTrue($response->isOk());
        $this->assertRedirectedTo('clients');
    }
    public function testClientCreate()
    {
        $this->visit('/clients/create')
            ->type('Taylor', 'name')
            -select('country')
            ->press('Create Client')
            ->see('Client Successfully created!')
            ->onPage('/clients');
            //->seePageIs('/clients');
    }

    public function testClientSave() {
        $client = new Client;

        $client->name = 'Idea7';
        $client->country = 'IN';
        $client->status = 'active';

        if (!$client->save()) {
            $errors = $client->getErrors()->all();
            echo 'Client Insert failed' . print_r($errors);
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
