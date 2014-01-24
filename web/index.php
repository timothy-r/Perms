<?php
use Silex\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->get('/', function(Application $app) { 
    return {"name" : "perms application"};
});

$app->run();
