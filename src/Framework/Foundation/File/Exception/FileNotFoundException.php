<?php


namespace WebRover\Framework\Foundation\File\Exception;


/**
 * Thrown when a file was not found.
 *
 * Class FileNotFoundException
 * @package WebRover\Framework\Foundation\File\Exception
 */
class FileNotFoundException extends FileException
{
    /**
     * @param string $path The path to the file that was not found
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The file "%s" does not exist', $path));
    }
}