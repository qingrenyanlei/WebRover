<?php


namespace WebRover\Framework\Config;


use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;

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

            $bundles = $app->getBundles();

            return new Config($configPath, $bundles);
        });
    }
}