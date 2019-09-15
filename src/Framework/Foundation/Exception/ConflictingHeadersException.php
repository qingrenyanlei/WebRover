<?php


namespace WebRover\Framework\Foundation\Exception;


/**
 * The HTTP request contains headers with conflicting information.
 *
 * Class ConflictingHeadersException
 * @package WebRover\Framework\Foundation\Exception
 */
class ConflictingHeadersException extends \UnexpectedValueException implements RequestExceptionInterface
{
}