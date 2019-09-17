<?php


namespace WebRover\Framework\Cache;


/**
 * Class Cache
 * @package WebRover\Framework\Cache
 */
class Cache implements CacheInterface
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param null $name
     * @return CacheInterface
     */
    public function store($name = null)
    {
        return $this->manager->getStore($name);
    }

    public function has($key)
    {
        return $this->store()->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->store()->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->store()->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->store()->delete($key);
    }

    public function clear()
    {
        return $this->store()->clear();
    }
}