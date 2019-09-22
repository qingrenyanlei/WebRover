<?php


namespace WebRover\Framework\Container;


/**
 * Interface ServiceProviderInterface
 * @package WebRover\Framework\Container
 */
interface ServiceProviderInterface
{
    public function register();

    public function boot();

    public function getNamespace();

    public function getName();
}