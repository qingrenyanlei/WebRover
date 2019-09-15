<?php


namespace WebRover\Framework\Support;


/**
 * Interface Jsonable
 * @package WebRover\Framework\Support
 */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0);
}