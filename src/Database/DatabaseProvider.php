<?php


namespace WebRover\Framework\Database;


use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;

/**
 * Class DatabaseProvider
 * @package WebRover\Framework\Database
 */
class DatabaseProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('db', function (Application $app) {

            $params = $app->make('config')
                ->get('service.database', []);

            return new Manager($params);
        });
    }
}