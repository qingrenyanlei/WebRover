<?php


namespace WebRover\Framework\Cache\Builder;


use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class Builder
{
    private $adapter;

    public function __construct(TagAwareAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function get($key)
    {
        if (is_array($key)) {
            return $this->getItems($key);
        }

        $item = $this->adapter->getItem($key);

        if (!$item->isHit()) {
            return null;
        }

        return $item->get();
    }

    public function getItems(array $keys)
    {
        $items = $this->adapter->getItems($keys);

        $result = [];

        foreach ($keys as $key) {
            $value = null;
            if (isset($items[$key])) {
                $value = $items[$key]->get();
            }

            $result[$key] = $value;
        }

        return $result;
    }

    public function set($key, $value, $ttl = 0)
    {
        $item = $this->adapter->getItem($key);


        $item->set($value);

        $ttl = max(0, intval($ttl));

        if ($ttl) {
            $item->expiresAfter($ttl);
        }

        return $this->adapter->save($item);
    }

    public function has($key)
    {
        return $this->adapter->hasItem($key);
    }

    public function delete($key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        return $this->adapter->deleteItems($key);
    }

    public function flush()
    {
        return $this->adapter->clear();
    }

    public function prune()
    {
        return $this->adapter->prune();
    }
}