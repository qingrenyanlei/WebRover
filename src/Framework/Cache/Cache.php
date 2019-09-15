<?php


namespace WebRover\Framework\Cache;


use WebRover\Framework\Cache\Builder\Builder;
use WebRover\Framework\Cache\Builder\TagBuilder;

class Cache
{
    use TagTrait;

    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;

        $this->builder = $this->getTag();
    }

    public function store($name = null)
    {
        $store = $this->manager->getStore($name);

        return new TagBuilder($store);
    }

    public function tag($tags)
    {
        $store = $this->manager->getStore();
        return new Tag($store, $tags);
    }

    /**
     * @param null $storeName
     * @return Builder
     */
    protected function getTag($storeName = null)
    {
        $store = $this->manager->getStore($storeName);

        return new Builder($store);
    }
}