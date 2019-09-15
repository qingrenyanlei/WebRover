<?php


namespace WebRover\Framework\Foundation\Session\Storage\Handler;


/**
 * Can be used in unit testing or in a situations where persisted sessions are not desired.
 *
 * Class NullSessionHandler
 * @package WebRover\Framework\Foundation\Session\Storage\Handler
 */
class NullSessionHandler extends AbstractSessionHandler
{
    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validateId($sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doRead($sessionId)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function updateTimestamp($sessionId, $data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($sessionId, $data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doDestroy($sessionId)
    {
        return true;
    }

    /**
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}