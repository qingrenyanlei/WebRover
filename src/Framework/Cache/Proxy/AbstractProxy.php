<?php


namespace WebRover\Framework\Cache\Proxy;


use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

abstract class AbstractProxy implements ProxyInterface
{
    /**
     * @var TagAwareAdapterInterface
     */
    protected static $store;

    public static function setStore($store)
    {
        self::$store = $store;
    }

    public function clear()
    {
        return self::$store->clear();
    }

    public function getItem($key)
    {
        return self::$store->getItem($key);
    }

    public function getItems(array $keys = [])
    {
        return self::$store->getItems($keys);
    }

    public function commit()
    {
        return self::$store->commit();
    }

    public function deleteItem($key)
    {
        return self::$store->deleteItem($key);
    }

    public function deleteItems(array $keys)
    {
        return self::$store->deleteItems($keys);
    }

    public function hasItem($key)
    {
        return self::$store->hasItem($key);
    }

    public function save(CacheItemInterface $item)
    {
        return self::$store->save($item);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return self::$store->saveDeferred($item);
    }

    public function invalidateTags(array $tags)
    {
        return self::$store->invalidateTags($tags);
    }
}