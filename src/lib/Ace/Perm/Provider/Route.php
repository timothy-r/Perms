<?php namespace Ace\Perm\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Ace\Perm\NotFoundException;
use Ace\Perm\Perm;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author timrodger
 * Date: 24/06/15
 */
class Route implements ServiceProviderInterface
{

    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $app->get('/',
            function(Application $app) {
                $data = ['name' => 'perms application'];
                return $app->json($data);
            });

        /**
         * Get all Perms for Subject
         */
        $app->get('/subject/{subject}',
            function(Application $app, $subject) {
                // obtain perms from storage, keyed by subject
                try {
                    $perm_instances = $app['store']->getAllForSubject($subject);
                    $data = [];
                    foreach ($perm_instances as $perm_instance){
                        $data []= ['perms' => $perm_instance->all(), 'subject' => $subject, 'object' => $perm_instance->object()];
                    }
                    return $app->json($data);
                } catch (NotFoundException $ex){
                    return new Response('',404);
                }
            });

        /**
         * Get all Subject Object pairs with this Subject and Perm
         */
        $app->get('/subject/{subject}/{perm}',
            function(Application $app, $subject, $perm) {
                // obtain perms from storage, keyed by subject
                try {
                    $perm_instances = $app['store']->getAllForSubjectWithPerm($subject, $perm);
                    $data = [];
                    foreach ($perm_instances as $perm_instance){
                        $data []= ['subject' => $subject, 'object' => $perm_instance->object()];
                    }
                    return $app->json($data);
                } catch (NotFoundException $ex){
                    return new Response('',404);
                }
            });

        /**
         * Get all Perms for Object
         */
        $app->get('/object/{object}',
            function(Application $app, $object) {
                // obtain perms from storage, keyed by object
                try {
                    $perm_instances = $app['store']->getAllForObject($object);
                    $data = [];
                    foreach ($perm_instances as $perm_instance){
                        $data []= ['perms' => $perm_instance->all(), 'object' => $object, 'subject' => $perm_instance->subject()];
                    }
                    return $app->json($data);
                } catch (NotFoundException $ex){
                    return new Response('',404);
                }
            });

        /**
         * Get all Subject Object pairs with this Object and Perm
         */
        $app->get('/object/{object}/{perm}',
            function(Application $app, $object, $perm) {
                // obtain perms from storage, keyed by subject
                try {
                    $perm_instances = $app['store']->getAllForObjectWithPerm($object, $perm);
                    $data = [];
                    foreach ($perm_instances as $perm_instance){
                        $data []= ['object' => $object, 'subject' => $perm_instance->subject()];
                    }
                    return $app->json($data);
                } catch (NotFoundException $ex){
                    return new Response('',404);
                }
            });

        /**
         * Get all perms for Subject Object pair
         */
        $app->get('/subject/{subject}/object/{object}',
            function(Application $app, $subject, $object) {
                // obtain perms from storage, keyed by subject & object
                try {
                    $perm_instance = $app['store']->get($subject, $object);
                    return $app->json([
                        'perms' => $perm_instance->all(),
                        'subject' => $subject,
                        'object' => $object,
                    ]);
                } catch (NotFoundException $ex){
                    return new Response('',404);
                }
            });

        /**
         * Set perm name on Subject Object pair
         */
        $app->put('/subject/{subject}/object/{object}/{perm}',
            function(Application $app, Request $request, $subject, $object, $perm) {
                try {
                    $perm_instance = $app['store']->get($subject, $object);
                } catch (NotFoundException $ex){
                    $perm_instance = new Perm($subject, $object);
                }
                $perm_instance->add($perm);
                $app['store']->update($perm_instance);
                // return 201 with no body
                return new Response('', 201);
            });

        /**
         * Test if Subject Object pair have perm set
         */
        $app->get('/subject/{subject}/object/{object}/{perm}',
            function(Application $app, Request $request, $subject, $object, $perm) {
                try{
                    $perm_instance = $app['store']->get($subject, $object);
                    if ($perm_instance->has($perm)){
                        return $app->json([
                            'subject' => $subject,
                            'object' => $object,
                        ]);
                    }
                } catch (NotFoundException $ex){
                }
                return new Response('', 404);
            });

        /**
         * Remove perm from Subject Object pair
         */
        $app->delete('/subject/{subject}/object/{object}/{perm}',
            function(Application $app, Request $request, $subject, $object, $perm) {
                try {
                    $perm_instance = $app['store']->get($subject, $object);
                    $perm_instance->remove($perm);
                    $app['store']->update($perm_instance);
                    return new Response('', 200);
                } catch (NotFoundException $ex){
                }
                return new Response('', 200);
            });

        /**
         * Remove all perms from Subject Object pair
         */
        $app->delete('/subject/{subject}/object/{object}',
            function(Application $app, Request $request, $subject, $object) {
                try {
                    $perm_instance = $app['store']->get($subject, $object);
                    $app['store']->remove($perm_instance);
                } catch (NotFoundException $ex) {
                }
                return new Response('', 200);
            });

    }
}