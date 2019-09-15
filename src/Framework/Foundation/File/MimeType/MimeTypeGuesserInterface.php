<?php


namespace WebRover\Framework\Foundation\File\MimeType;


use WebRover\Framework\Foundation\File\Exception\AccessDeniedException;
use WebRover\Framework\Foundation\File\Exception\FileNotFoundException;

/**
 * Guesses the mime type of a file.
 *
 * Interface MimeTypeGuesserInterface
 * @package WebRover\Framework\Foundation\File\MimeType
 */
interface MimeTypeGuesserInterface
{
    /**
     * Guesses the mime type of the file with the given path.
     *
     * @param string $path The path to the file
     *
     * @return string|null The mime type or NULL, if none could be guessed
     *
     * @throws FileNotFoundException If the file does not exist
     * @throws AccessDeniedException If the file could not be read
     */
    public function guess($path);
}