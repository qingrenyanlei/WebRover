<?php


namespace WebRover\Framework\Foundation\Session;


use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use WebRover\Framework\Encryption\Encrypter;

/**
 * Class EncryptSessionHandlerProxy
 * @package WebRover\Framework\Foundation\Session
 */
class EncryptSessionHandlerProxy extends SessionHandlerProxy
{
    /**
     * @var Encrypter
     */
    private $encrypter;

    /**
     * EncryptSessionHandlerProxy constructor.
     * @param \SessionHandlerInterface $handler
     * @param Encrypter $encrypter
     */
    public function __construct(\SessionHandlerInterface $handler, Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
        parent::__construct($handler);
    }

    /**
     * @param $sessionId
     * @return mixed|string
     * @throws \Exception
     */
    public function read($sessionId)
    {
        $data = parent::read($sessionId);

        if ($data) {
            $data = $this->encrypter->decryptString($data);
        }

        return $data;
    }

    /**
     * @param $sessionId
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function write($sessionId, $data)
    {
        $data = $this->encrypter->encryptString($data);

        return parent::write($sessionId, $data);
    }
}