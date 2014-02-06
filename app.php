<?php
use Silex\Application;
use Ace\Perm\Store;
use Ace\Perm\SubjectType;
use Ace\Perm\ObjectType;

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
    
    $subject = new SubjectType($subject_id, $subject_type);
    $object = new ObjectType($object_id, $object_type);

    // obtain perms from storage, keyed by subject & object
    $perm = $store->get($subject, $object);
    //$perms = new StdClass;
    $data = ['perms' => $perm, 'subject' => $subject, 'object' => $object]; 
    return $app->json($data);
});

return $app;
