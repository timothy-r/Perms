<?php
use Silex\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->get('/', function(Application $app) { 
    $data = ['name' => 'perms application'];
    return $app->json($data);
});

$app->get('/{subject_type}/{subject_id}/{object_type}/{object_id}', function(Application $app, $subject_type, $subject_id, $object_type, $object_id) {
    
    $subject = ['type' => $subject_type, 'id' => $subject_id];
    $object = ['type' => $object_type, 'id' => $object_id];

    // obtain perms from storage
    $perms = new StdClass;
    $data = ['perms' => $perms, 'subject' => $subject, 'object' => $object]; 
    return $app->json($data);
});

$app->run();
