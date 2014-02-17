<?php

use Silex\WebTestCase;
use Silex\Application;

class GetPermTest extends WebTestCase
{
    protected $subject = 'user-111@acnts.net';
    protected $object = 'thing:88@objects.net';
    
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->base_url = "/subject/{$this->subject}/object/{$this->object}";
        $this->client = $this->createClient();
        $this->client->request('DELETE', "{$this->base_url}");
    }

    public function tearDown()
    {
        $this->client->request('DELETE', "{$this->base_url}");
        parent::tearDown();
    }

    public function createApplication()
    {
        return require __DIR__ . '/../../app-test.php';
    }

    public function testGetPermFailsForMissingPerm()
    {
         $crawler = $this->client->request('GET', "{$this->base_url}");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testGetPermSuccess()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/write");
         $crawler = $this->client->request('GET', "{$this->base_url}");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testPutPermSuccess()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $this->client->getResponse()->getStatusCode());
    }

    public function testPutPermSucceedsEveryTimeItIsCalled()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $this->client->getResponse()->getStatusCode());
         $crawler = $this->client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $this->client->getResponse()->getStatusCode());
         $crawler = $this->client->request('PUT', "{$this->base_url}/write");
         $this->assertSame(201, $this->client->getResponse()->getStatusCode());
    }

    public function testGetReturns404IfPermDoesNotExist()
    {
         $crawler = $this->client->request('HEAD', "{$this->base_url}/admin");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testGetReturns200IfPermDoesExist()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/control");
         $crawler = $this->client->request('HEAD', "{$this->base_url}/control");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteReturns200WhenPermIsMissing()
    {
         $crawler = $this->client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
         $crawler = $this->client->request('DELETE', "{$this->base_url}/to-delete");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
         $crawler = $this->client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteReturns200WhenPermExists()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/to-delete");
         $crawler = $this->client->request('PUT', "{$this->base_url}/to-delete");
         $crawler = $this->client->request('DELETE', "{$this->base_url}/to-delete");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
         $crawler = $this->client->request('GET', "{$this->base_url}/to-delete");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
        
    public function testDeleteAllReturns200WhenNoPermsExist()
    {
         $crawler = $this->client->request('DELETE', "{$this->base_url}");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetSubjectReturns404IfSubjectDoesNotExist()
    {
         $crawler = $this->client->request('GET', "/subject/1010");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetSubjectReturns200IfSubjectDoesExist()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/read");
         $crawler = $this->client->request('GET', "/subject/{$this->subject}");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAllForSubjectWithPermReturns404IfSubjectIsMissing()
    {
         $crawler = $this->client->request('GET', "/subject/1010/admin");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetAllForSubjectWithPermReturns200ForExistingSubject()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/read");
         $crawler = $this->client->request('GET', "/subject/{$this->subject}/read");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetObjectReturns404IfObjectDoesNotExist()
    {
         $crawler = $this->client->request('GET', "/object/1010");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetObjectReturns200IfObjectDoesExist()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/read");
         $crawler = $this->client->request('GET', "/object/{$this->object}");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetAllForObjectWithPermReturns404IfObjectIsMissing()
    {
         $crawler = $this->client->request('GET', "/object/1010/admin");
         $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }
    
    public function testGetAllForObjectWithPermReturns200ForExistingObject()
    {
         $crawler = $this->client->request('PUT', "{$this->base_url}/read");
         $crawler = $this->client->request('GET', "/object/{$this->object}/read");
         $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
    
}
