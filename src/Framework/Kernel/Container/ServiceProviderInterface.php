<?php


namespace WebRover\Framework\Kernel\Container;


interface ServiceProviderInterface
{
    public function register();

    public function getNamespace();

    public function getName();
}