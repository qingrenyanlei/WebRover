<?php


namespace WebRover\Framework\Foundation\File\MimeType;


use WebRover\Framework\Foundation\File\Exception\AccessDeniedException;
use WebRover\Framework\Foundation\File\Exception\FileNotFoundException;

/**
 * Guesses the mime type using the PECL extension FileInfo.
 *
 * Class FileinfoMimeTypeGuesser
 * @package WebRover\Framework\Foundation\File\MimeType
 */
class FileinfoMimeTypeGuesser implements MimeTypeGuesserInterface
{
    private $magicFile;

    /**
     * @param string $magicFile A magic file to use with the finfo instance
     *
     * @see https://php.net/finfo-open
     */
    public function __construct($magicFile = null)
    {
        $this->magicFile = $magicFile;
    }

    /**
     * Returns whether this guesser is supported on the current OS/PHP setup.
     *
     * @return bool
     */
    public static function isSupported()
    {
        return \function_exists('finfo_open');
    }

    /**
     * {@inheritdoc}
     */
    public function guess($path)
    {
        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new AccessDeniedException($path);
        }

        if (!self::isSupported()) {
            return null;
        }

        if (!$finfo = new \finfo(FILEINFO_MIME_TYPE, $this->magicFile)) {
            return null;
        }

        return $finfo->file($path);
    }
}