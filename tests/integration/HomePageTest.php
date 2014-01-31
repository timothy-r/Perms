<?php

use Silex\WebTestCase;

class HomePageTest extends WebTestCase
{
    
    protected function createApplication()
    {
        return require __DIR__ . '/../../web/index.php';
    }

    public function testHomePage()
    {
        
    }
}

