<?php


namespace WebRover\Framework\Routing\Exception;


/**
 * The resource was not found.
 *
 * This exception should trigger an HTTP 404 response in your application code.
 *
 * Class ResourceNotFoundException
 * @package WebRover\Framework\Routing\Exception
 */
class ResourceNotFoundException extends \RuntimeException implements ExceptionInterface
{
}