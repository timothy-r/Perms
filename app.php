<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ace\Perm\Store;

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

$app->get('/subject/{subject}/object/{object}', 
function(Application $app, $subject, $object) use ($store) {
    
    // obtain perms from storage, keyed by subject & object
    $perm = $store->get($subject, $object);
    $data = [
        'perms' => $perm->allPerms(), 
        'subject' => $subject,
        'object' => $object,
    ];
    return $app->json($data);
});

$app->put('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
   
    $perm_object = $store->add($subject, $object, $perm);
    // return 201 with no body
    return new Response('', 201);
});

$app->get('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
   
    $perm_object = $store->get($subject, $object);
    if ($perm_object->hasPerm($perm)){
        $data = [
            'perms' => $perm,
            'subject' => $subject,
            'object' => $object,
        ];
        return $app->json($data);
    } else {
        return new Response('', 404);
    }
});

$app->delete('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
   
    $perm_object = $store->get($subject, $object);
    if ($perm_object->hasPerm($perm)){
        // remove perm
        $store->remove($perm_object, $perm);
        return new Response('', 200);
    } else {
        return new Response('', 200);
    }
});

$app->delete('/subject/{subject}/object/{object}', 
function(Application $app, Request $request, $subject, $object) use ($store) {
   
    $perm_object = $store->get($subject, $object);
    $store->remove($perm_object);

    return new Response('', 200);
});

return $app;
