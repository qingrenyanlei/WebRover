<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Simple\PdoCache;

/**
 * Class PdoStore
 * @package WebRover\Framework\Cache\Store
 */
class PdoStore extends AbstractStore
{
    public function connect(array $params = [])
    {
        $connection = $params['connection'];

        $options = isset($params['options']) ? $params['options'] : [];

        $this->instance = new PdoCache($connection, '', 0, $options);
    }
}