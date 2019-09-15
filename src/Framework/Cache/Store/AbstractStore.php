<?php


namespace WebRover\Framework\Cache\Store;


use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

abstract class AbstractStore implements AdapterInterface,StoreInterface
{
    /**
     * @var AdapterInterface
     */
    protected $instance;

    public function commit()
    {
        return $this->instance->commit();
    }

    public function hasItem($key)
    {
        return $this->instance->hasItem($key);
    }

    public function deleteItem($key)
    {
        return $this->instance->deleteItem($key);
    }

    public function deleteItems(array $keys)
    {
        return $this->instance->deleteItems($keys);
    }

    public function clear()
    {
        return $this->instance->clear();
    }

    public function save(CacheItemInterface $item)
    {
        return $this->instance->save($item);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->instance->saveDeferred($item);
    }

    public function getItem($key)
    {
        return $this->instance->getItem($key);
    }

    public function getItems(array $keys = [])
    {
        return $this->instance->getItems($keys);
    }
}