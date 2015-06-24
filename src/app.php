<?php

use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;


use Ace\Perm\Provider\RDBMSStore as StoreProvider;
use Ace\Perm\Provider\Route as RouteProvider;

require_once __DIR__ . '/vendor/autoload.php';

$debug = isset($debug) ? $debug : false;
$environment = isset($environment) ? $environment : 'prod';

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
        'path'     => __DIR__.'/../data/perm.db',
    ],
]);

$app->register(new StoreProvider());
$app->register(new RouteProvider());

return $app;
