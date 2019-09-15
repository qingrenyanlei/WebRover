<?php


namespace WebRover\Framework\Kernel;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Foundation\Response;

/**
 * Terminable extends the Kernel request/response cycle with dispatching a post
 * response event after sending the response and before shutting down the kernel.
 *
 * Interface TerminableInterface
 * @package WebRover\Framework\Kernel
 */
interface TerminableInterface
{
    /**
     * Terminates a request/response cycle.
     *
     * Should be called after sending the response and before shutting down the kernel.
     */
    public function terminate(Request $request, Response $response);
}