<?php
use Silex\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->get('/', function(Application $app) { 
    $data = ['name' => 'perms application'];
    return $app->json($data);
});

$app->run();
