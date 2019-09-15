<?php


namespace WebRover\Framework\Kernel\Facade;


use WebRover\Framework\Kernel\Container\Container;

/**
 * Class AbstractFacade
 * @package WebRover\Framework\Kernel\Facade
 */
abstract class AbstractFacade implements FacadeInterface
{
    /**
     * @return mixed
     */
    protected static function makeFacade()
    {
        $facadeClass = static::getFacadeClass();

        return Container::getInstance()->make($facadeClass);
    }

    /**
     * @param $method
     * @param $params
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::makeFacade(), $method], $params);
    }
}