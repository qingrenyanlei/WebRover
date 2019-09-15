<?php


namespace WebRover\Framework\Cache\Proxy;



use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

interface ProxyInterface extends TagAwareAdapterInterface
{
    public static function setStore($store);
}