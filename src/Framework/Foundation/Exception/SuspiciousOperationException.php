<?php


namespace WebRover\Framework\Foundation\Exception;


/**
 * Raised when a user has performed an operation that should be considered
 * suspicious from a security perspective.
 *
 * Class SuspiciousOperationException
 * @package WebRover\Framework\Foundation\Exception
 */
class SuspiciousOperationException extends \UnexpectedValueException implements RequestExceptionInterface
{
}