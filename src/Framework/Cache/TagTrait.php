<?php


namespace WebRover\Framework\Cache;


use WebRover\Framework\Cache\Builder\Builder;

trait TagTrait
{
    /**
     * @var Builder
     */
    private $builder;

    public function has($key)
    {
        return $this->builder->has($key);
    }

    public function set($key, $value, $ttl = 0)
    {
        return $this->builder->set($key, $value, $ttl);
    }

    public function get($key)
    {
        return $this->builder->get($key);
    }

    public function delete($key)
    {
        return $this->builder->delete($key);
    }

    public function flush()
    {
        return $this->builder->flush();
    }
}