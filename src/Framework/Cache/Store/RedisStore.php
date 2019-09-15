<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class RedisStore
 * @package WebRover\Framework\Cache\Store
 */
class RedisStore extends AbstractStore
{
    public function connect(array $params)
    {
        $dsn = $params['dsn'];

        $options = isset($params['options']) ? $params['options'] : [];

        $client = RedisAdapter::createConnection($dsn, $options);

        $this->instance = new RedisAdapter($client);

        return $this;
    }
}