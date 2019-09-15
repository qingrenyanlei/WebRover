<?php


namespace WebRover\Framework\Monolog;


use Monolog\Handler\StreamHandler;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

/**
 * Class LoggerProvider
 * @package WebRover\Framework\Monolog
 */
class LoggerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('logger', function (Application $app) {

            $config = $app->make('config')->get('service.logger', []);

            $log = new \Monolog\Logger('app');

            $path = isset($config['path']) && $config['path'] ? $config['path'] : storage_path('logs');

            $environment = $app->getEnvironment();

            $stream = $path . '/' . $environment . '.log';

            $level = isset($config['level']) ? $config['level'] : \Monolog\Logger::DEBUG;

            $bubble = (bool)(isset($config['bubble']) ? $config['bubble'] : true);

            $filePermission = isset($config['filePermission']) ? $config['filePermission'] : null;

            $useLocking = (bool)(isset($config['userLocking']) ? $config['userLocking'] : false);

            $log->pushHandler(new StreamHandler($stream, $level, $bubble, $filePermission, $useLocking));
            return $log;
        });
    }
}