<?php


namespace WebRover\Framework\Foundation\Session;


use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use WebRover\Framework\Support\Str;

/**
 * Class Session
 * @package WebRover\Framework\Foundation\Session
 */
class Session extends SymfonySession
{
    public function __construct(SessionStorageInterface $storage = null, AttributeBagInterface $attributes = null, FlashBagInterface $flashes = null)
    {
        parent::__construct($storage, $attributes, $flashes);

        if (!$this->has('_token')) {
            $this->regenerateToken();
        }
    }

    public function token()
    {
        return $this->get('_token');
    }

    public function regenerateToken()
    {
        $this->set('_token', Str::random(40));
    }
}