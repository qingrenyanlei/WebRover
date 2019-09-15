<?php


namespace WebRover\Framework\Config;


use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

/**
 * Class ConfigProvider
 * @package WebRover\Framework\Config
 */
class ConfigProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 注册配置服务
         */
        $this->app->singleton('config', function (Application $app) {
            $configPath = $app->getConfigPath();

            $modules = $app->getModules();

            return new Config($configPath, $modules);
        });
    }
}