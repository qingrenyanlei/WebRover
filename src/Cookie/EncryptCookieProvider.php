<?php


namespace WebRover\Framework\Cookie;


use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Cookie\Listener\CookieListener;
use WebRover\Framework\Kernel\Application;

/**
 * Class EncryptCookieProvider
 * @package WebRover\Framework\Cookie
 */
class EncryptCookieProvider extends ServiceProvider
{
    protected $except = [];

    public function register()
    {
        $this->app->singleton(CookieListener::class, function (Application $app) {

            $encrypter = $app->make('encrypter');

            return new CookieListener($encrypter, $this->except);
        });
    }
}