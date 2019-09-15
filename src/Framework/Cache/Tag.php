<?php


namespace WebRover\Framework\Cache;


use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class Tag
{
    private $adapter;

    private $tags;

    public function __construct(TagAwareAdapterInterface $adapter, $tags)
    {
        $this->adapter = $adapter;
        $this->tags = is_array($tags) ? $tags : [$tags];
    }

    public function set($key, $value, $ttl = 0)
    {
        $item = $this->adapter->getItem($key);

        $item->tag($this->tags)->set($value);

        $ttl = max(0, intval($ttl));

        if ($ttl) {
            $item->expiresAfter($ttl);
        }

        return $this->adapter->save($item);
    }

    public function flush()
    {
        return $this->adapter->invalidateTags($this->tags);
    }
}