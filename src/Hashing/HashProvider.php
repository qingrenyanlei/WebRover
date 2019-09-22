<?php


namespace WebRover\Framework\Hashing;



use WebRover\Framework\Container\ServiceProvider;

/**
 * Class HashProvider
 * @package WebRover\Framework\Hashing
 */
class HashProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('hash', function () {
            return new BcryptHasher();
        });
    }
}