<?php


namespace WebRover\Framework\Cache\Proxy;


use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use WebRover\Framework\Cache\CacheInterface;

/**
 * Interface ProxyInterface
 * @package WebRover\Framework\Cache\Proxy
 */
interface ProxyInterface extends CacheInterface
{
    public static function setStore(SimpleCacheInterface $store);

    public function set($key, $value, $ttl = 0);

    public function get($key, $default = null);

    public function has($key);

    public function delete($key);

    public function clear();
}