<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

use Ace\Perm\Provider\RDBMSStore as StoreProvider;
use Ace\Perm\Provider\Route as RouteProvider;
use Ace\Perm\Provider\ErrorHandler as ErrorHandlerProvider;
use Ace\Perm\Provider\Log as LogProvider;

require_once __DIR__ . '/vendor/autoload.php';

$debug = false;

$app = new Application;
$app['debug'] = $debug;

$app->register(new LogProvider());
$app->register(new ErrorHandlerProvider());

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../data/perm.db',
    ],
]);

$app->register(new StoreProvider());
$app->register(new RouteProvider());

return $app;
