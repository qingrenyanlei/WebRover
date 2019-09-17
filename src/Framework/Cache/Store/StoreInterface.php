<?php


namespace WebRover\Framework\Cache\Store;


use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use WebRover\Framework\Cache\CacheInterface;

interface StoreInterface extends CacheInterface, SimpleCacheInterface
{
    public function connect(array $params = []);
}