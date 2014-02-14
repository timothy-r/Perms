<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ace\Perm\Store;
use Ace\Perm\SubjectType;
use Ace\Perm\ObjectType;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application;
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/data/app.db',
    ],
]);

$store = new Store($app['db']);

$app->get('/', 
function(Application $app) { 
    $data = ['name' => 'perms application'];
    return $app->json($data);
});

$app->get('/{subject_type}/{subject_id}/{object_type}/{object_id}', 
function(Application $app, $subject_type, $subject_id, $object_type, $object_id) use ($store) {
    
    $subject = new SubjectType($subject_id, $subject_type);
    $object = new ObjectType($object_id, $object_type);

    // obtain perms from storage, keyed by subject & object
    $perm = $store->get($subject, $object);
    $data = [
        'perms' => $perm->allPerms(), 
        'subject' => ['id' => $subject->getId(), 'type' => $subject->getType()], 
        'object' => ['id' => $object->getId(), 'type' => $object->getType()]]; 
    return $app->json($data);
});

$app->put('/{subject_type}/{subject_id}/{object_type}/{object_id}/{perm}', 
function(Application $app, Request $request, $subject_type, $subject_id, $object_type, $object_id, $perm) use ($store) {
   
    $subject = new SubjectType($subject_id, $subject_type);
    $object = new ObjectType($object_id, $object_type);

    $perm_object = $store->add($subject, $object, $perm);
    // return 201 with no body?
    return new Response('', 201);
});

$app->get('/{subject_type}/{subject_id}/{object_type}/{object_id}/{perm}', 
function(Application $app, Request $request, $subject_type, $subject_id, $object_type, $object_id, $perm) use ($store) {
   
    $subject = new SubjectType($subject_id, $subject_type);
    $object = new ObjectType($object_id, $object_type);

    $perm_object = $store->get($subject, $object);
    if ($perm_object->hasPerm($perm)){
        $data = [
            'perms' => $perm,
            'subject' => ['id' => $subject->getId(), 'type' => $subject->getType()], 
            'object' => ['id' => $object->getId(), 'type' => $object->getType()]]; 
        return $app->json($data);
    } else {
        return new Response('', 404);
    }
});

return $app;
