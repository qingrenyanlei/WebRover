<?php


namespace WebRover\Framework\Cache\Proxy;


use Psr\SimpleCache\CacheInterface;

/**
 * Class AbstractProxy
 * @package WebRover\Framework\Cache\Proxy
 */
abstract class AbstractProxy implements ProxyInterface
{
    /**
     * @var CacheInterface
     */
    protected static $store;

    public static function setStore(CacheInterface $store)
    {
        static::$store = $store;
    }

    public function has($key)
    {
        return static::$store->has($key);
    }

    public function get($key, $default = null)
    {
        return static::$store->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return static::$store->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return static::$store->delete($key);
    }

    public function clear()
    {
        return static::$store->clear();
    }
}