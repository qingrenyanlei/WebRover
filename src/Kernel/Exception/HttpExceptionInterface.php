<?php


namespace WebRover\Framework\Kernel\Exception;


/**
 * Interface for HTTP error exceptions.
 *
 * Interface HttpExceptionInterface
 * @package WebRover\Framework\Kernel\Exception
 */
interface HttpExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode();

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders();
}