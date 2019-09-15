<?php


namespace WebRover\Framework\Foundation\File;


/**
 * A PHP stream of unknown size.
 *
 * Class Stream
 * @package WebRover\Framework\Foundation\File
 */
class Stream extends File
{
    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return false;
    }
}