<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Simple\MemcachedCache;

/**
 * Class MemcachedStore
 * @package WebRover\Framework\Cache\Store
 */
class MemcachedStore extends AbstractStore
{
    public function connect(array $params = [])
    {
        $servers = $params['servers'];

        $options = isset($params['options']) ? $params['options'] : [];

        $client = MemcachedCache::createConnection($servers, $options);

        $this->instance = new MemcachedCache($client);
    }
}