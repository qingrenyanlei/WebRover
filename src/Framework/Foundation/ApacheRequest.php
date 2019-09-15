<?php


namespace WebRover\Framework\Foundation;


/**
 * Request represents an HTTP request from an Apache server.
 *
 * Class ApacheRequest
 * @package WebRover\Framework\Foundation
 */
class ApacheRequest extends Request
{
    /**
     * {@inheritdoc}
     */
    protected function prepareRequestUri()
    {
        return $this->server->get('REQUEST_URI');
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareBaseUrl()
    {
        $baseUrl = $this->server->get('SCRIPT_NAME');

        if (false === strpos($this->server->get('REQUEST_URI'), $baseUrl)) {
            // assume mod_rewrite
            return rtrim(\dirname($baseUrl), '/\\');
        }

        return $baseUrl;
    }
}