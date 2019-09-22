<?php


namespace WebRover\Framework\Foundation;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Foundation\Session\EncryptSessionHandlerProxy;
use WebRover\Framework\Foundation\Session\Session;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Support\Connection\Memcached;

/**
 * Class FoundationProvider
 * @package WebRover\Framework\Foundation
 */
class FoundationProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 注册请求栈服务
         */
        $this->app->singleton('request_stack', function () {
            return new RequestStack();
        });

        /**
         * 注册Session服务
         */
        $this->app->singleton('session', function (Application $app) {

            $config = $app->make('config')->get('service.session', []);

            $default = $config['default'];

            $store = $config['handlers'][$default];

            switch ($store['handler']) {
                case 'memcached':
                    $client = $store['client'];
                    $clientServers = $client['servers'];
                    $clientOptions = isset($client['options']) && is_array($client['options']) ? $client['options'] : [];
                    $client = Memcached::createConnection($clientServers, $clientOptions);
                    $options = isset($store['options']) && is_array($store['options']) ? $store['options'] : [];
                    $handler = new MemcachedSessionHandler($client, $options);
                    break;

                case 'pdo':
                    $connection = $store['connection'];
                    $options = isset($store['options']) && is_array($store['options']) ? $store['options'] : [];
                    $handler = new PdoSessionHandler($connection, $options);
                    break;

                case 'file':
                default:
                    $path = isset($store['path']) && $store['path'] ? $store['path'] : null;
                    $handler = new NativeFileSessionHandler($path);
            }

            $encrypt = (bool)(isset($config['encrypt']) ? $config['encrypt'] : false);

            if ($encrypt) {
                $proxy = new EncryptSessionHandlerProxy($handler, $app->make('encrypter'));
            } else {
                $proxy = new SessionHandlerProxy($handler);
            }

            $sessionOptions = isset($config['options']) && is_array($config['options']) ? $config['options'] : [];

            $storage = new NativeSessionStorage($sessionOptions, $proxy);

            return new Session($storage);
        });
    }
}