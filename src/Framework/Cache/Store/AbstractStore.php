<?php


namespace WebRover\Framework\Cache\Store;


use Psr\SimpleCache\CacheInterface;

abstract class AbstractStore implements StoreInterface
{
    /**
     * @var CacheInterface
     */
    protected $instance;

    public function has($key)
    {
        return $this->instance->has($key);
    }

    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMultiple($key, $default);
        }

        return $this->instance->get($key, $default);
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->instance->getMultiple($keys, $default);
    }

    public function set($key, $value, $ttl = 0)
    {
        if (is_array($key)) {
            return $this->setMultiple($key, $value);
        }

        return $this->instance->set($key, $value, $ttl);
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->instance->setMultiple($values, $ttl);
    }

    public function delete($key)
    {
        if (is_array($key)) {
            return $this->deleteMultiple($key);
        }

        return $this->instance->delete($key);
    }

    public function deleteMultiple($keys)
    {
        return $this->instance->deleteMultiple($keys);
    }

    public function clear()
    {
        return $this->instance->clear();
    }
}