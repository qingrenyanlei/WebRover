<?php


namespace WebRover\Framework\Kernel\Exception;


/**
 * Class PreconditionFailedHttpException
 * @package WebRover\Framework\Kernel\Exception
 */
class PreconditionFailedHttpException extends HttpException
{
    /**
     * @param string $message The internal exception message
     * @param \Exception $previous The previous exception
     * @param int $code The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(412, $message, $previous, [], $code);
    }
}
