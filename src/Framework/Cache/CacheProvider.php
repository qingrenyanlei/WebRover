<?php


namespace WebRover\Framework\Cache;


use WebRover\Framework\Cache\Proxy\EncryptProxy;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

/**
 * Class CacheProvider
 * @package WebRover\Framework\Cache
 */
class CacheProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cache', function (Application $app) {

            $params = $app->make('config')->get('service.cache', []);

            $db = $app->make('db');

            $encrypt = isset($params['encrypt']) ? $params['encrypt'] : true;

            $proxy = null;

            if ((bool)$encrypt) {
                $proxy = new EncryptProxy($app->make('encrypter'));
            }

            $manager = new Manager($params, $db, $proxy);

            return new Cache($manager);
        });
    }
}