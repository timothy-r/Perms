<?php

use Silex\WebTestCase;
use Silex\Application;

class GetPermTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function createApplication()
    {
        return require __DIR__ . '/../../app.php';
    }

    public function testGetPermSuccess()
    {
         $client = $this->createClient();
         $crawler = $client->request('GET', '/user/111/thing/88');
         $this->assertTrue($client->getResponse()->isOk());
    }

    public function testPutPermSuccess()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', '/user/111/thing/88/write');
         $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testPutPermSucceedsMultipleTimes()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', '/user/111/thing/88/write');
         $this->assertSame(201, $client->getResponse()->getStatusCode());
         $crawler = $client->request('PUT', '/user/111/thing/88/write');
         $this->assertSame(201, $client->getResponse()->getStatusCode());
         $crawler = $client->request('PUT', '/user/111/thing/88/write');
         $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testGetReturns404IfPermDoesNotExist()
    {
         $client = $this->createClient();
         $crawler = $client->request('HEAD', '/user/111/thing/88/admin');
         $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testGetReturns200IfPermDoesExist()
    {
         $client = $this->createClient();
         $crawler = $client->request('PUT', '/user/111/thing/88/control');
         $crawler = $client->request('HEAD', '/user/111/thing/88/control');
         $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
