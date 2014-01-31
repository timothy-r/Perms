<?php

use Silex\WebTestCase;

class HomePageTest extends WebTestCase
{
    
    public function createApplication()
    {
        return require __DIR__ . '/../../web/index.php';
    }

    public function testHomePage()
    {
        
    }
}

