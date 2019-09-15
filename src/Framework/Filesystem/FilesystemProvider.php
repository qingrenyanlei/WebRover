<?php


namespace WebRover\Framework\Filesystem;


use Symfony\Component\Filesystem\Filesystem;
use WebRover\Framework\Kernel\Container\ServiceProvider;

/**
 * Class FilesystemProvider
 * @package WebRover\Framework\Filesystem
 */
class FilesystemProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 注册文件系统服务
         */
        $this->app->singleton('filesystem', function () {
            return new Filesystem();
        });
    }
}