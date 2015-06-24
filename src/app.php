<?php

use Silex\Application;

use Ace\Perm\Provider\RDBMSStore as StoreProvider;
use Ace\Perm\Provider\Route as RouteProvider;
use Ace\Perm\Provider\ErrorHandler as ErrorHandlerProvider;
use Ace\Perm\Provider\Log as LogProvider;

require_once __DIR__ . '/vendor/autoload.php';

$debug = isset($debug) ? $debug : false;
$environment = isset($environment) ? $environment : 'prod';


$app = new Application;
$app['debug'] = $debug;

$app->register(new LogProvider());

$app->register(new ErrorHandlerProvider());

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
