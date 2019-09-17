<?php


namespace WebRover\Framework\Cache\Facade;


use WebRover\Framework\Cache\CacheInterface;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Cache
 * @package WebRover\Framework\Cache\Facade
 * @mixin \WebRover\Framework\Cache\Cache
 * @method CacheInterface store($name = null) static
 * @method bool has($key) static
 * @method bool set($key, $value, $ttl = null) static
 * @method mixed get($key, $default = null) static
 * @method bool delete($key) static
 * @method bool clear() static
 */
class Cache extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'cache';
    }
}