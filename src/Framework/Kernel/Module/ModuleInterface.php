<?php


namespace WebRover\Framework\Kernel\Module;


/**
 * Interface ModuleInterface
 * @package WebRover\Framework\Kernel\Module
 */
interface ModuleInterface
{
    public function getNamespace();

    public function getPath();

    public function getName();

    public function getConfigPath();

    public function getResourcePath();

    public function registerProviders();

    public function boot();

    public function shutdown();
}