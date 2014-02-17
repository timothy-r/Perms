<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

use Ace\Perm\Store;
use Ace\Perm\Perm;
use Ace\Perm\NotFoundException;

$debug = false;

require_once __DIR__ . '/vendor/autoload.php';

// register to convert errors into Exceptions
ErrorHandler::register();

// handle fatal errors
ExceptionHandler::register($debug);

$app = new Application;
$app['debug'] = $debug;

/**
* @todo configure different databases in testing versus development and production
*/
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/data/dev.db',
    ],
]);

$store = new Store($app['db']);

require(__DIR__.'/routes.php');

return $app;
