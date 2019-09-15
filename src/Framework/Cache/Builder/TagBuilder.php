<?php


namespace WebRover\Framework\Cache\Builder;


use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use WebRover\Framework\Cache\Tag;
use WebRover\Framework\Cache\TagTrait;

class TagBuilder
{
    use TagTrait;

    private $adapter;

    public function __construct(TagAwareAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->builder = new Builder($this->adapter);
    }

    public function tag($tags)
    {
        return new Tag($this->adapter, $tags);
    }
}