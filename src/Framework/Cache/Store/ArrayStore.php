<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Simple\ArrayCache;

/**
 * Class ArrayStore
 * @package WebRover\Framework\Cache\Store
 */
class ArrayStore extends AbstractStore
{
    public function connect(array $params = [])
    {
        $this->instance = new ArrayCache();
    }
}