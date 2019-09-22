<?php


namespace WebRover\Framework\Encryption;


use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Support\Str;

/**
 * Class EncryptionProvider
 * @package WebRover\Framework\Encryption
 */
class EncryptionProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('encrypter', function (Application $app) {

            $config = $app->make('config')->get('service.encrypter', []);

            if (Str::startsWith($key = $config['key'], 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            $cipher = $config['cipher'];

            return new Encrypter($key, $cipher);
        });
    }
}