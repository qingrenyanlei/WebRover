<?php


namespace WebRover\Framework\Foundation\File\Exception;


/**
 * Class UnexpectedTypeException
 * @package WebRover\Framework\Foundation\File\Exception
 */
class UnexpectedTypeException extends FileException
{
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type %s, %s given', $expectedType, \is_object($value) ? \get_class($value) : \gettype($value)));
    }
}