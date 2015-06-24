<?php namespace Ace\Perm\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Ace\Perm\Store\RDBMSStore as Store;


/**
 * @author timrodger
 * Date: 24/06/15
 */
class RDBMSStore implements ServiceProviderInterface
{

    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $app['store'] = new Store($app['db']);
    }
}