<?php

use Silex\WebTestCase;
use Silex\Application;

class GetPermTest extends WebTestCase
{
    
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
}
