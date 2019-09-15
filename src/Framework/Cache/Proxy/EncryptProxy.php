<?php


namespace WebRover\Framework\Cache\Proxy;


use Psr\Cache\CacheItemInterface;
use WebRover\Framework\Security\DecryptException;
use WebRover\Framework\Security\Encryption;

class EncryptProxy extends AbstractProxy
{
    private $encryption;

    public function __construct(Encryption $encryption)
    {
        $this->encryption = $encryption;
    }

    public function getItem($key)
    {
        $item = parent::getItem($key);

        if ($item) {
            $value = $item->get();

            if ($value) {
                try {
                    $this->encryption->decryptString($value);
                } catch (DecryptException $exception) {
                }
            }
        }
        $item->set($value);

        return $item;
    }

    public function getItems(array $keys = [])
    {
        $items = parent::getItems($keys);

        foreach ($keys as $key) {
            if (!isset($items[$key])) {
                continue;
            }

            $item = $items[$key];

            $value = $item->get();

            if ($value) {
                try {
                    $value = $this->encryption->decryptString($value);
                } catch (DecryptException $exception) {
                }
            }
            $item->set($value);

            $items[$key] = $item;
        }

        return $items;
    }

    public function save(CacheItemInterface $item)
    {
        $value = $this->encryption->encrypt($item->get());

        $item->set($value);
        return parent::save($item);
    }
}