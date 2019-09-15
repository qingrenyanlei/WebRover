<?php


namespace WebRover\Framework\Security;


use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

/**
 * Class SecurityProvider
 * @package WebRover\Framework\Security
 */
class SecurityProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('encrypter', function (Application $app) {

            $config = $app->make('config')->get('service.security.encrypt', []);

            $key = $config['key'];

            $cipher = $config['cipher'];

            return new Encryption($key, $cipher);
        });
    }
}