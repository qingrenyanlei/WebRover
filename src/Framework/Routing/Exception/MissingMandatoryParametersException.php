<?php


namespace WebRover\Framework\Routing\Exception;


/**
 * Exception thrown when a route cannot be generated because of missing
 * mandatory parameters.
 *
 * Class MissingMandatoryParametersException
 * @package WebRover\Framework\Routing\Exception
 */
class MissingMandatoryParametersException extends \InvalidArgumentException implements ExceptionInterface
{
}