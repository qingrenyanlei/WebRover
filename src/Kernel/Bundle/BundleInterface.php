<?php


namespace WebRover\Framework\Kernel\Bundle;


/**
 * Interface BundleInterface
 * @package WebRover\Framework\Kernel\Bundle
 */
interface BundleInterface
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