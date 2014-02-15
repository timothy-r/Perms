<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ace\Perm\Store;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;

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
    try {
        $perm = $store->get($subject, $object);
        return $app->json([
            'perms' => $perm->all(), 
            'subject' => $subject,
            'object' => $object,
        ]);
    } catch (NotFoundException $ex){
        return new Response('',404);
    }
});

$app->put('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
    try {
        $perm_object = $store->get($subject, $object);
    } catch (NotFoundException $ex){
        $perm_object = new Perm($subject, $object);
    }
    $perm_object->add($perm);
    $store->update($perm_object); 
    // return 201 with no body
    return new Response('', 201);
});

// @todo not super sure about this route - just use regular GET on subject/object and then parse result
$app->get('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
    try{
        $perm_object = $store->get($subject, $object);
        if ($perm_object->has($perm)){
            return $app->json([
                $perm => true,
                'subject' => $subject,
                'object' => $object,
            ]);
        }
    } catch (NotFoundException $ex){
    }
    return new Response('', 404);
});

$app->delete('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
    try {
        $perm_object = $store->get($subject, $object);
        $perm_object->remove($perm);
        $store->update($perm_object);
        return new Response('', 200);
    } catch (NotFoundException $ex){
    }
    return new Response('', 200);
});

$app->delete('/subject/{subject}/object/{object}', 
function(Application $app, Request $request, $subject, $object) use ($store) {
    
    try {
        $perm_object = $store->get($subject, $object);
        $store->remove($perm_object);
    } catch (NotFoundException $ex) {
    }
    return new Response('', 200);
});

return $app;
