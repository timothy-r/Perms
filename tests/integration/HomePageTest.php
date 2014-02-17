<?php

use Silex\WebTestCase;
use Silex\Application;

class HomePageTest extends WebTestCase
{
    
    public function createApplication()
    {
        return require __DIR__ . '/../../app-test.php';
    }

    public function testHomePageSuccess()
    {
         $client = $this->createClient();
         $crawler = $client->request('GET', '/');
         $this->assertTrue($client->getResponse()->isOk());
    }
}

