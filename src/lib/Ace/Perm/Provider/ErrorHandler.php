<?php namespace Ace\Perm\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ErrorHandler as SymfonyErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

use Exception;


/**
 * Handles exceptions
 */
class ErrorHandler implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        // register to convert errors into Exceptions
        SymfonyErrorHandler::register();

        // handle fatal errors
        ExceptionHandler::register($debug = false);
    }

    public function boot(Application $app)
    {
        $app->error(function (Exception $e) use($app) {
            $app['logger']->addError($e->getMessage());
            return new Response($e->getMessage());
        });
    }
}