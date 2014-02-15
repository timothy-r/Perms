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

$app->get('/subject/{subject}', 
function(Application $app, $subject) use ($store) {
    // obtain perms from storage, keyed by subject 
    try {
        $perm_instances = $store->getForSubject($subject);
        $data = [];
        foreach ($perm_instances as $perm_instance){
            $data []= ['perms' => $perm_instance->all(), 'subject' => $subject, 'object' => $perm_instance->object()];
        }
        return $app->json($data);
    } catch (NotFoundException $ex){
        return new Response('',404);
    }
});


$app->get('/subject/{subject}/object/{object}', 
function(Application $app, $subject, $object) use ($store) {
    // obtain perms from storage, keyed by subject & object
    try {
        $perm_instance = $store->get($subject, $object);
        return $app->json([
            'perms' => $perm_instance->all(), 
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
        $perm_instance = $store->get($subject, $object);
    } catch (NotFoundException $ex){
        $perm_instance = new Perm($subject, $object);
    }
    $perm_instance->add($perm);
    $store->update($perm_instance); 
    // return 201 with no body
    return new Response('', 201);
});

// @todo not super sure about this route - just use regular GET on subject/object and then parse result
$app->get('/subject/{subject}/object/{object}/{perm}', 
function(Application $app, Request $request, $subject, $object, $perm) use ($store) {
    try{
        $perm_instance = $store->get($subject, $object);
        if ($perm_instance->has($perm)){
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
        $perm_instance = $store->get($subject, $object);
        $perm_instance->remove($perm);
        $store->update($perm_instance);
        return new Response('', 200);
    } catch (NotFoundException $ex){
    }
    return new Response('', 200);
});

$app->delete('/subject/{subject}/object/{object}', 
function(Application $app, Request $request, $subject, $object) use ($store) {
    
    try {
        $perm_instance = $store->get($subject, $object);
        $store->remove($perm_instance);
    } catch (NotFoundException $ex) {
    }
    return new Response('', 200);
});

return $app;
