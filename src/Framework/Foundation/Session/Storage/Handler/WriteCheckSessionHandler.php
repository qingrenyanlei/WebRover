<?php


namespace WebRover\Framework\Foundation\Session\Storage\Handler;


/**
 * Wraps another SessionHandlerInterface to only write the session when it has been modified.
 *
 * Class WriteCheckSessionHandler
 * @package WebRover\Framework\Foundation\Session\Storage\Handler
 */
class WriteCheckSessionHandler implements \SessionHandlerInterface
{
    private $wrappedSessionHandler;

    /**
     * @var array sessionId => session
     */
    private $readSessions;

    public function __construct(\SessionHandlerInterface $wrappedSessionHandler)
    {
        @trigger_error(sprintf('The %s class is deprecated since Symfony 3.4 and will be removed in 4.0. Implement `SessionUpdateTimestampHandlerInterface` or extend `AbstractSessionHandler` instead.', self::class), E_USER_DEPRECATED);

        $this->wrappedSessionHandler = $wrappedSessionHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return $this->wrappedSessionHandler->close();
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return $this->wrappedSessionHandler->destroy($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return $this->wrappedSessionHandler->gc($maxlifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return $this->wrappedSessionHandler->open($savePath, $sessionName);
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $session = $this->wrappedSessionHandler->read($sessionId);

        $this->readSessions[$sessionId] = $session;

        return $session;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        if (isset($this->readSessions[$sessionId]) && $data === $this->readSessions[$sessionId]) {
            return true;
        }

        return $this->wrappedSessionHandler->write($sessionId, $data);
    }
}