<?php


namespace WebRover\Framework\Config\Loader;


use WebRover\Framework\Config\ParameterBag;

/**
 * Interface LoaderInterface
 * @package WebRover\Framework\Config\Loader
 */
interface LoaderInterface
{
    /**
     * @param $filePath
     * @return ParameterBag
     */
    public function parse($filePath);

    /**
     * @param $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null);

    /**
     * @return mixed
     */
    public function extension();

}