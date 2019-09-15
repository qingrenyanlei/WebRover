<?php


namespace WebRover\Framework\Kernel\ControllerMetadata;


/**
 * Builds method argument data.
 *
 * Interface ArgumentMetadataFactoryInterface
 * @package WebRover\Framework\Kernel\ControllerMetadata
 */
interface ArgumentMetadataFactoryInterface
{
    /**
     * @param mixed $controller The controller to resolve the arguments for
     *
     * @return ArgumentMetadata[]
     */
    public function createArgumentMetadata($controller);
}