<?php


namespace WebRover\Framework\Foundation\Session\Storage\Proxy;


use WebRover\Framework\Security\Encryption;

/**
 * Class EncryptSessionHandlerProxy
 * @package WebRover\Framework\Foundation\Session\Storage\Proxy
 */
class EncryptSessionHandlerProxy extends SessionHandlerProxy
{
    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * EncryptSessionHandlerProxy constructor.
     * @param \SessionHandlerInterface $handler
     * @param Encryption $encryption
     */
    public function __construct(\SessionHandlerInterface $handler, Encryption $encryption)
    {
        $this->encryption = $encryption;

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
            $data = $this->encryption->decryptString($data);
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
        $data = $this->encryption->encryptString($data);

        return parent::write($sessionId, $data);
    }
}