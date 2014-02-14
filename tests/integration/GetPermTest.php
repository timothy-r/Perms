<?php

use Silex\WebTestCase;
use Silex\Application;

class GetPermTest extends WebTestCase
{
    protected $base_url = '/subject/user-111@acnts.net/object/thing:88@objects.net';

    public function tearDown()
    {
        $client = $this->createClient();
        $crawler = $client->request('DELETE', "{$this->base_url}/write");
        $crawler = $client->request('DELETE', "{$this->base_url}/admin");
        $crawler = $client->request('DELETE', "{$this->base_url}/control");
        $crawler = $client->request('DELETE', "{$this->base_url}/to-delete");
        parent::tearDown();
    }

    public function createApplication()
    {
        return require __DIR__ . '/../../app.php';
    }

    public function testGetPermFailsForMissingPerm()
    {
         $client = $this->createClient();
         $crawler = $client->request('GET', "{$this->base_url}");
         $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testGetPermSuccess()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', "{$this->base_url}/write");
         $crawler = $client->request('GET', "{$this->base_url}");
         $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testPutPermSuccess()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testPutPermSucceedsMultipleTimes()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $client->getResponse()->getStatusCode());
         $crawler = $client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $client->getResponse()->getStatusCode());
         $crawler = $client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testGetReturns404IfPermDoesNotExist()
    {
         $client = $this->createClient();
         $crawler = $client->request('HEAD', "{$this->base_url}/admin");
         $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testGetReturns200IfPermDoesExist()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', "{$this->base_url}/control");
         $crawler = $client->request('HEAD', "{$this->base_url}/control");
         $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteReturns200WhenPermIsMissing()
    {
         $client = $this->createClient();
         $crawler = $client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $client->getResponse()->getStatusCode());
         $crawler = $client->request('DELETE', "{$this->base_url}/to-delete");
         $this->assertSame(200, $client->getResponse()->getStatusCode());
         $crawler = $client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteReturns200WhenPermExists()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', "{$this->base_url}/to-delete");
         $crawler = $client->request('PUT', "{$this->base_url}/to-delete");
         $crawler = $client->request('DELETE', "{$this->base_url}/to-delete");
         $this->assertSame(200, $client->getResponse()->getStatusCode());
         $crawler = $client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $client->getResponse()->getStatusCode());
    }
        
    public function testDeleteAllReturns200WhenNoPermsExist()
    {
         $client = $this->createClient();
         $crawler = $client->request('DELETE', "{$this->base_url}");
         $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
    
}
