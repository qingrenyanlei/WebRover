<?php


namespace WebRover\Framework\Cache\Store;


/**
 * Interface StoreInterface
 * @package WebRover\Framework\Cache\Store
 */
interface StoreInterface
{
    /**
     * @param array $params
     */
    public function connect(array $params);
}