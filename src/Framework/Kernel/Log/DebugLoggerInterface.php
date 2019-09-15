<?php


namespace WebRover\Framework\Kernel\Log;


/**
 * Interface DebugLoggerInterface
 * @package WebRover\Framework\Kernel\Log
 * @method clear() Removes all log records.
 */
interface DebugLoggerInterface
{
    /**
     * Returns an array of logs.
     *
     * A log is an array with the following mandatory keys:
     * timestamp, message, priority, and priorityName.
     * It can also have an optional context key containing an array.
     *
     * @return array An array of logs
     */
    public function getLogs();

    /**
     * Returns the number of errors.
     *
     * @return int The number of errors
     */
    public function countErrors();
}