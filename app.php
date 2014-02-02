<?php
use Silex\Application;
use Ace\Perm\Store;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ],
]);

$store = new Store($app['db']);

$app->get('/', function(Application $app) { 
    $data = ['name' => 'perms application'];
    return $app->json($data);
});

$app->get('/{subject_type}/{subject_id}/{object_type}/{object_id}', 
function(Application $app, $subject_type, $subject_id, $object_type, $object_id) use ($store) {
    
    $subject = ['type' => $subject_type, 'id' => $subject_id];
    $object = ['type' => $object_type, 'id' => $object_id];

    // obtain perms from storage, keyed by subject & object
    // $perms = $store->getPerms($subject, $object);
    $perms = new StdClass;
    $data = ['perms' => $perms, 'subject' => $subject, 'object' => $object]; 
    return $app->json($data);
});

return $app;
