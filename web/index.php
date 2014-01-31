<?php
use Silex\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->get('/', function(Application $app) { 
    $data = ['name' => 'perms application'];
    return $app->json($data);
});

$app->get('/user/{user_id}/{type}/{object_id}', function(Application $app, $user_id, $type, $object_id) {
    $perms = new StdClass;
    $subject = ['user' => $user_id];
    $object = ['type' => $type, 'id' => $object_id];
    $data = ['perms' => $perms, 'subject' => $subject, 'object' => $object]; 
    return $app->json($data);
});

$app->run();
