<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Adapter\MemcachedAdapter;

/**
 * Class MemcachedStore
 * @package WebRover\Framework\Cache\Store
 */
class MemcachedStore extends AbstractStore
{
    public function connect(array $params)
    {
        $servers = $params['servers'];

        $options = isset($params['options']) ? $params['options'] : [];

        $client = MemcachedAdapter::createConnection($servers, $options);

        $this->instance = new MemcachedAdapter($client);

        return $this;
    }
}